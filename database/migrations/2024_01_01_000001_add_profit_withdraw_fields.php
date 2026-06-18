<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfitWithdrawFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Добавляем поля в settings
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'profit_wallet_type')) {
                $table->string('profit_wallet_type', 50)->default('qiwi');
            }
            if (!Schema::hasColumn('settings', 'profit_wallet_address')) {
                $table->string('profit_wallet_address', 255)->default('');
            }
            if (!Schema::hasColumn('settings', 'profit_withdraw_threshold')) {
                $table->decimal('profit_withdraw_threshold', 12, 2)->default(500);
            }
            if (!Schema::hasColumn('settings', 'profit_auto_withdraw')) {
                $table->boolean('profit_auto_withdraw')->default(0);
            }
        });

        // Создаём таблицу истории
        if (!Schema::hasTable('profit_withdraws')) {
            Schema::create('profit_withdraws', function (Blueprint $table) {
                $table->id();
                $table->decimal('amount', 12, 2);
                $table->string('wallet_type', 50)->default('qiwi');
                $table->string('wallet_address', 255);
                $table->string('status', 50)->default('pending');
                $table->string('txid', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['profit_wallet_type', 'profit_wallet_address', 'profit_withdraw_threshold', 'profit_auto_withdraw']);
        });
        Schema::dropIfExists('profit_withdraws');
    }
}
