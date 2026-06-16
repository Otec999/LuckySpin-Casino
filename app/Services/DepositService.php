<?php

namespace App\Services;

use App\Payment;
use App\User;
use App\Status;
use App\Setting;

/**
 * Сервис для обработки зачисления депозитов.
 * Устраняет дублирование кода во всех callback-методах PaymentController.
 */
class DepositService
{
    /**
     * Обработать успешное зачисление депозита.
     *
     * @param string $transactionId Уникальный ID транзакции
     * @param float $amount Сумма пополнения
     * @return string Ответ для платежной системы ('OK' или die)
     */
    public function processDeposit(string $transactionId, float $amount): string
    {
        // Проверяем существование платежа
        $payment = Payment::where('transaction', $transactionId)->first();
        if (!$payment) {
            die('Ошибка: платеж не найден');
        }

        // Проверяем, не был ли уже зачислен
        if ($payment->status == 1) {
            die('Ошибка: платеж уже обработан');
        }

        // Начисляем процент от промокода
        $percent = $payment->percent;
        $amountWithPercent = $amount + ($amount * $percent / 100);

        $user = User::where('id', $payment->user_id)->first();
        if (!$user) {
            die('Ошибка: пользователь не найден');
        }

        $refId = $user->ref_id;

        // Обновляем статус платежа
        $payment->status = 1;
        $payment->afterpay = $user->balance + $amountWithPercent;
        $payment->save();

        // Обновляем статус пользователя (ранговая система)
        $this->updateUserStatus($user, $amountWithPercent);

        // Обновляем пользователя
        $user = User::where('id', $user->id)->first();
        $user->bonus_up = ($user->deps == 0 && $user->balance > 5) ? 1 : 0;
        $user->balance += $amountWithPercent;
        $user->deps += $amountWithPercent;
        $user->sum_to_withdraw += ($amountWithPercent * 0.1); // 10% от суммы
        $user->save();

        // Начисляем реферальное вознаграждение
        if ($refId > 0) {
            $this->processReferral($refId, $amountWithPercent, $user);
        }

        return 'OK';
    }

    /**
     * Обновить статус пользователя в зависимости от суммы депозитов.
     */
    private function updateUserStatus(User $user, float $amount): void
    {
        $userStatus = $user->status;
        $userDeps = $user->deps + $amount;

        $maxId = Status::max('id');
        if ($maxId != $userStatus) {
            $statuses = Status::where('id', '>', $userStatus)->orderBy('id', 'asc')->get();
            foreach ($statuses as $st) {
                if ($userDeps >= $st->deposit) {
                    // Начисляем бонус за новый статус
                    $u = User::where('id', $user->id)->first();
                    $u->balance += $st->bonus;
                    $u->status = $st->id;
                    $u->save();
                }
            }
        }
    }

    /**
     * Начислить реферальное вознаграждение.
     */
    private function processReferral(int $refId, float $amount, User $user): void
    {
        $userRef = User::where('id', $refId)->first();
        if (!$userRef) return;

        $percentRef = $userRef->ref_coeff ?: 0;
        $refBonus = $amount * $percentRef / 100;

        $userRef->profit += $refBonus;
        $userRef->balance_ref += $refBonus;
        $userRef->save();
    }
}
