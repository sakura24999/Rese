<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reminder_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(false);
            $table->time('send_time')->default('09:00:00');
            $table->integer('days_before')->default(1);
            $table->text('email_template')->nullable();
            $table->string('email_subject')->default('【Rese】ご予約のリマインダー');
            $table->timestamps();
        });

        DB::table('reminder_settings')->insert([
            'is_enabled' => false,
            'send_time' => '09:00:00',
            'days_before' => 1,
            'email_template' => '{{shop_name}}での予約が{{reservation_date}}{{reservation_time}}に予定されています。',
            'email_subject' => '【Rese】ご予約のリマインダー',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminder_settings');
    }
};
