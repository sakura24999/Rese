<?php

namespace App\Mail;

use App\Models\ReminderSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReservationReminder extends Mailable
{
    use Queueable, SerializesModels;

    protected $reservation;
    protected $settings;
    protected $testData;

    /**
     * Create a new message instance.
     */
    public function __construct($reservation, $settings = null)
    {
        if (is_array($reservation)) {
            $this->testData = $reservation;
            $this->reservation = null;
        } else {
            $this->reservation = $reservation;
            $this->testData = null;
        }

        $this->settings = $settings ?? ReminderSettings::getSettings();
    }

    public function build()
    {
        Log::debug('メール送信を試みています。パラメータ確認');

        if ($this->testData) {
            $data = $this->testData;
            Log::debug('テストデータ使用:' . json_encode($data));
        } else {
            $data = [
                'user_name' => $this->reservation->user->name,
                'shop_name' => $this->reservation->shop->name,
                'reservation_date' => date('Y年m月d日', strtotime($this->reservation->reserved_at)),
                'reservation_time' => date('H:i', strtotime($this->reservation->reserved_at)),
                'number_of_guests' => $this->reservation->number_of_guests . '名',
            ];
            Log::debug('通常データ使用:' . json_encode($data));
        }

        $template = $this->settings->email_template;
        $message = $template;

        foreach ($data as $key => $value) {
            $message = str_replace('{{' . $key . '}}', $value, $message);
        }

        Log::debug('メール送信を試みています。参照ビュー: emails.reservation-reminder');

        return $this->subject($this->settings->email_subject)
            ->view('emails.reservation-reminder')
            ->with([
                'emailMessage' => $message,
                'data' => $data
            ]);
    }
}
