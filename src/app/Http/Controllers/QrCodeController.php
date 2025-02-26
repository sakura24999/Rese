<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\Builder\Builder;
use Exception;

class QrCodeController extends Controller
{
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);

        $this->authorize('view', $reservation);

        return view('qrcode.show', compact('reservation'));
    }

    /**
     * Summary of generate
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function generate($id): Response
    {
        Log::info('QRコード生成開始:' . $id);
        try {
            $reservation = Reservation::findOrFail($id);

            $this->authorize('view', $reservation);

            if (!$reservation->verification_code) {
                $reservation->verification_code = Str::random(20);
                $reservation->save();
            }

            $qrData = json_encode([
                'reservation_id' => $reservation->id,
                'shop_id' => $reservation->shop_id,
                'user_id' => $reservation->user_id,
                'date' => $reservation->date . ' ' . $reservation->time,
                'code' => $reservation->verification_code,
            ]);

            Log::info('QRコード生成:' . $qrData);

            $qrCode = new QrCode($qrData);

            $writer = new PngWriter();
            $pngData = $writer->write($qrCode)->getString();

            Log::info('QRコード生成完了');

            return response($pngData)->header('Content-Type', 'image/png');
        } catch (Exception $e) {
            Log::error('QRコード生成エラー:' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->file(public_path('images/error-qrcode.png'));
        }

    }

    public function verify(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['code'])) {
            return response()->json(['success' => false, 'message' => '予約が見つかりません']);
        }

        if ($request->user()->cannot('verifyAsShop', $reservation)) {
            return response()->json(['success' => false, 'message' => '権限がありません']);
        }

        $reservation->check_in_at = now();
        $reservation->save();

        return response()->json([
            'success' => true,
            'message' => '来店確認完了',
            'reservation' => $reservation->load('user', 'shop')
        ]);
    }
}
