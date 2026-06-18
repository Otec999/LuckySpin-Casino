<?php

/**
 * Возвращает отформатированную сумму с символом валюты
 * 
 * Примеры:
 *   showPrice(100)     → "100 ₽"   (при RUB)
 *   showPrice(100)     → "100 ₼"   (при AZN)
 *   showPrice(100, 2)  → "100.00 ₽"
 */
function showPrice($amount, $decimals = 0)
{
    $setting = \App\Setting::first();
    $symbol = $setting->currency_symbol ?? '₽';
    $currency = $setting->currency ?? 'RUB';
    
    $formatted = number_format((float)$amount, $decimals, '.', ' ');
    
    // Для некоторых валют символ ставим перед суммой
    $beforeCurrencies = ['USD', 'USDT', 'BTC'];
    if (in_array($currency, $beforeCurrencies)) {
        return $symbol . $formatted;
    }
    
    // Для остальных — после (RUB, AZN, TRY, UAH)
    return $formatted . ' ' . $symbol;
}

/**
 * Конвертирует сумму из RUB в текущую валюту
 * (для FreeKassa передаём в RUB, для отображения конвертируем)
 */
function convertFromRUB($rubAmount)
{
    $setting = \App\Setting::first();
    $rate = (float)($setting->currency_rate ?? 1);
    if ($rate <= 0) $rate = 1;
    
    return round($rubAmount / $rate, 2);
}

/**
 * Конвертирует сумму из текущей валюты в RUB
 * (для передачи в FreeKassa)
 */
function convertToRUB($localAmount)
{
    $setting = \App\Setting::first();
    $rate = (float)($setting->currency_rate ?? 1);
    if ($rate <= 0) $rate = 1;
    
    return round($localAmount * $rate, 2);
}
