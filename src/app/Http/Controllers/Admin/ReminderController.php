<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReminderSettings;
use Illuminate\Support\Facades\Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationReminder;
use Illuminate\Support\Facades\Log;

class ReminderController extends Controller
{
    public function index()
    {
        $settings = ReminderSettings::getSettings();

        return view('admin.reminder.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'is_enabled' => 'required|boolean',
            'send_time' => 'required|date_format:H:i',
            'days_before' => 'required|integer|min:0|max:7',
            'email_subject' => 'required|string|max:255',
            'email_template' => 'required|string',
        ]);

        $settings = ReminderSettings::getSettings();
        $settings->update($validated);

        return redirect()->route('admin.reminder.index')->with('success', 'リマインダー設定が更新されました');
    }

    public function sendTest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $settings = ReminderSettings::getSettings();

        $testData = [
            'user_name' => 'テストユーザー',
            'shop_name' => 'テスト店舗',
            'reservation_date' => date('Y年m月d日'),
            'reservation_time' => '18:00',
            'number_of_guests' => '2名',
        ];

        Log::debug('リマインダーテストメール送信開始');
        Log::debug('使用テンプレート:' . $settings->email_template);

        try {
            $mail = new ReservationReminder($testData, $settings);
            Mail::to($request->email)->send($mail);
            Log::debug('メール送信成功');
        } catch (Exception $e) {
            Log::error('メール送信エラー:' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->route('admin.reminder.index')->with('error', 'メール送信中にエラーが発生しました:' . $e->getMessage());
        }

        return redirect()->route('admin.reminder.index')->with('success', 'テストメールが送信されました');
    }
}
