<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'shop_id' => $request->shop_id,
            'date' => $request->date,
            'time' => $request->time,
            'number_of_people' => $request->number_of_people,
        ]);

        return redirect()->route('done');
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            Log::info('キャンセル権限エラー', [
                'reservation_id' => $reservation->id,
                'request_user_id' => auth()->id(),
                'reservation_user_id' => $reservation->user_id
            ]);
            return response()->json(['message' => '権限がありません'], 403);
        }

        try {
            Log::info('キャンセルリクエスト受信', [
                'reservation_id' => $reservation->id,
                'user_id' => auth()->id(),
                'request_time' => now()
            ]);

            $reservation->delete();

            Log::info('キャンセル成功', [
                'reservation_id' => $reservation->id,
                'status' => 'deleted'
            ]);

            return response()->json(['message' => '予約をキャンセルしました']);
        } catch (\Exception $e) {
            Log::error('予約キャンセルエラー:' . $e->getMessage());
            return response()->json(['message' => 'キャンセルに失敗しました'], 500);
        }
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        try {

            Log::info('予約更新開始:', [
                'reservation_id' => $reservation->id,
                'request_data' => $request->validated(),
                'current_reservation' => $reservation->toArray()
            ]);

            DB::beginTransaction();

            $conflictingReservation = Reservation::where('shop_id', $reservation->shop_id)->where('date', $request->date)->where('time', $request->time)->where('id', '!=', $reservation->id)->exists();

            Log::info('予約重複チェック結果:', [
                'has_conflict' => $conflictingReservation,
                'check_conditions' => [
                    'shop_id' => $reservation->shop_id,
                    'date' => $request->date,
                    'time' => $request->time
                ]
            ]);

            if ($conflictingReservation) {
                return response()->json(['message' => 'この時間帯は既に予約が入っています'], 422);
            }

            $reservation->update($request->validated());

            Log::info('予約更新成功:', [
                'updated_reservation' => $reservation->fresh()->toArray()
            ]);

            DB::commit();

            return response()->json([
                'message' => '予約を更新しました',
                'reservation' => $reservation
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reservation update failed:' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->validated(),
                'reservation_id' => $reservation->id
            ]);
            return response()->json(['message' => '予約の更新に失敗しました'], 500);
        }
    }

    public function done()
    {
        return view('done');
    }
}
