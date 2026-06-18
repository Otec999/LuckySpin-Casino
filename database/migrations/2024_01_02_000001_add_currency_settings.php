<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'currency')) {
                $table->string('currency', 10)->default('RUB');
            }
            if (!Schema::hasColumn('settings', 'currency_rate')) {
                $table->decimal('currency_rate', 12, 4)->default(1.0000);
            }
            if (!Schema::hasColumn('settings', 'currency_symbol')) {
                $table->string('currency_symbol', 10)->default('₽');
            }
        });

        // Устанавливаем дефолтные данные для существующих записей
        DB::table('settings')->update([
            'currency' => 'RUB',
            'currency_rate' => 1.0000,
            'currency_symbol' => '₽'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['currency', 'currency_rate', 'currency_symbol']);
        });
    }
}
