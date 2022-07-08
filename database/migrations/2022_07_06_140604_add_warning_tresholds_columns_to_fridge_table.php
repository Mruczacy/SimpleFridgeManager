<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarningTresholdsColumnsToFridgeTable extends Migration
{
    public function up(): void
    {
        Schema::table('fridges', function (Blueprint $table) {
            $table->integer('throw_it_out_treshold')->default(0);
            $table->integer('asap_treshold')->default(2);
            $table->integer('in_near_future_treshold')->default(7);
        });
    }

    public function down(): void
    {
        Schema::table('fridges', function (Blueprint $table) {
            $table->dropColumn('throw_it_out_treshold');
            $table->dropColumn('asap_treshold');
            $table->dropColumn('in_near_future_treshold');
        });
    }
}
