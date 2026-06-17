<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\App;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Директива для перевода в шаблонах: @lang('key')
        Blade::directive('lang', function ($expression) {
            $locale = session('locale', 'ru');
            return "<?php echo \\App\\Helpers\\LanguageHelper::trans($expression, [], '$locale'); ?>";
        });
        
        // Директива для выбора языка: @t('key')
        Blade::directive('t', function ($expression) {
            return "<?php echo \\App\\Helpers\\LanguageHelper::trans($expression); ?>";
        });
    }
}
