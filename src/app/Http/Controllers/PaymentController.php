<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Exception;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function processPayment(Request $request, Reservation $reservation)
    {
        try {
            if ($reservation->is_paid) {
                return response()->json(['message' => 'この予約は既に支払い済みです'], 400);
            }

            if ($reservation->user_id !== auth()->id()) {
                return response()->json(['message' => '不正なリクエストです'], 403);
            }

            $validated = $request->validate([
                'payment_method_id' => 'required|string',
                'amount' => 'required|numeric|min:100'
            ]);

            Stripe::setApiKey(config('services.stripe.secret'));

            $amount = $validated['amount'];

            // PaymentIntentの作成
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'jpy',
                'payment_method' => $validated['payment_method_id'],
                'confirmation_method' => 'manual',
                'confirm' => true,
                'metadata' => [
                    'reservation_id' => $reservation->id,
                    'user_id' => auth()->id(),
                    'shop_id' => $reservation->shop_id
                ]
            ]);

            if ($paymentIntent->status === 'succeeded') {
                $reservation->update([
                    'is_paid' => true,
                    'payment_id' => $paymentIntent->id,
                    'paid_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => '決済が完了しました'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => '決済処理に失敗しました',
                    'payment_intent' => $paymentIntent
                ], 400);
            }
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Stripe APIエラー: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            return response()->json([
                'message' => '予期せぬエラーが発生しました'
            ], 500);
        }
    }

    public function processDemo(Request $request, Reservation $reservation)
    {
        try {
            if ($reservation->is_paid) {
                return response()->json([
                    'success' => false,
                    'message' => 'この予約は既に支払い済みです'
                ], 400);
            }

            $validated = $request->validate([
                'demo_mode' => 'required|boolean',
                'amount' => 'required|numeric|min:100'
            ]);

            $reservation->update([
                'is_paid' => true,
                'payment_id' => 'demo_' . uniqid(),
                'paid_at' => now()
            ]);

            Log::info('デモ決済が処理されました', [
                'reservation_id' => $reservation->id,
                'user_id' => auth()->id(),
                'shop_id' => $reservation->shop_id,
                'amount' => $validated['amount']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'デモモードでの決済が完了しました'
            ]);
        } catch (Exception $e) {
            Log::error('デモ決済エラー: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'デモ決済処理中にエラーが発生しました: ' . $e->getMessage()
            ], 500);
        }
    }
}
