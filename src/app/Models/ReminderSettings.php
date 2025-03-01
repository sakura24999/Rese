<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_enabled',
        'send_time',
        'days_before',
        'email_template',
        'email_subject',
    ];

    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'is_enabled' => false,
                'send_time' => '09:00:00',
                'days_before' => 1,
                'email_template' => '{{shop_name}}での予約が{{reservation_date}}{{reservation_time}}に予定されています。',
                'email_subject' => '【Rese】ご予約のリマインダー',
            ]);
        }

        return $settings;
    }
}
