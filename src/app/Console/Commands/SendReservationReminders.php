<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Models\ReminderSettings;
use App\Mail\ReservationReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reservation-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '予約リマインダーメールを送信します';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = ReminderSettings::getSettings();

        if (!$settings->is_enabled) {
            $this->info('リマインダー機能は無効になっています');
            return 0;
        }

        $targetDate = Carbon::now()->addDays($settings->days_before)->format('Y-m-d');

        $reservations = Reservation::whereDate('reserved_at', $targetDate)->where('status', 'confirmed')->whereNull('canceled_at')->with('user', 'shop')->get();

        $count = 0;

        foreach ($reservations as $reservation) {
            if (!$reservation->user->email) {
                continue;
            }

            Mail::to($reservation->user->email)->send(new ReservationReminder($reservation, $settings));

            $count++;
        }

        $this->info("予約リマインダーが{$count}件送信されました。");
        return 0;
    }
}
