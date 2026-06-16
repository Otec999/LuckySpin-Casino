<?php

namespace App\Traits;

use App\HistoryBalance;
use App\User;
use Illuminate\Support\Facades\Cache;

/**
 * Трейт для управления историей баланса пользователя.
 * 
 * Заменяет дублирующийся код записи в Redis-кеш на единый метод.
 * Для обратной совместимости продолжает писать в Redis.
 */
trait HandlesBalanceHistory
{
    /**
     * Записать событие изменения баланса в историю.
     *
     * @param int|User $user Объект пользователя или его ID
     * @param string $type Тип операции
     * @param float $balanceBefore Баланс до
     * @param float $balanceAfter Баланс после
     * @return void
     */
    protected function writeBalanceHistory($user, string $type, float $balanceBefore, float $balanceAfter): void
    {
        $userId = $user instanceof User ? $user->id : $user;
        
        // 1. Пишем в БД (новая система)
        try {
            HistoryBalance::create([
                'user_id' => $userId,
                'type' => $type,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
            ]);
        } catch (\Exception $e) {
            // Логируем, но не блокируем операцию
            \Log::error('Failed to write balance history to DB: ' . $e->getMessage());
        }

        // 2. Пишем в Redis (обратная совместимость)
        try {
            $cacheKey = 'user.' . $userId . '.historyBalance';
            $history = Cache::get($cacheKey);
            if (!$history) {
                $history = '[]';
            }
            $historyArr = json_decode($history, true) ?? [];
            $historyArr[] = [
                'user_id' => $userId,
                'type' => $type,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'date' => date('d.m.Y H:i'),
            ];
            Cache::put($cacheKey, json_encode($historyArr));
        } catch (\Exception $e) {
            \Log::error('Failed to write balance history to Redis: ' . $e->getMessage());
        }
    }

    /**
     * Инициализировать историю баланса в Redis (если отсутствует).
     *
     * @param int $userId
     * @return void
     */
    protected function ensureBalanceHistoryCache(int $userId): void
    {
        $cacheKey = 'user.' . $userId . '.historyBalance';
        if (!Cache::has($cacheKey)) {
            Cache::put($cacheKey, '[]');
        }
    }
}
