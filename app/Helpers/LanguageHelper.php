<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class LanguageHelper
{
    /**
     * Get translation for a given key
     */
    public static function trans($key, $replace = [], $locale = null)
    {
        if ($locale === null) {
            $locale = session('locale', 'ru');
        }
        
        App::setLocale($locale);
        
        return trans("messages.$key", $replace);
    }

    /**
     * Get available languages
     */
    public static function getLanguages()
    {
        return [
            'ru' => [
                'name' => 'Русский',
                'flag' => '🇷🇺',
                'short' => 'RU',
            ],
            'en' => [
                'name' => 'English',
                'flag' => '🇬🇧',
                'short' => 'EN',
            ],
            'az' => [
                'name' => 'Azərbaycan',
                'flag' => '🇦🇿',
                'short' => 'AZ',
            ],
        ];
    }

    /**
     * Get current language info
     */
    public static function getCurrentLanguage()
    {
        $locale = session('locale', 'ru');
        $languages = self::getLanguages();
        
        return $languages[$locale] ?? $languages['ru'];
    }
}
