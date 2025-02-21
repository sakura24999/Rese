<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DELETE t1 FROM shops t1
        INNER JOIN shops t2
        WHERE t1.id > t2.id
        AND t1.name = t2.name');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
