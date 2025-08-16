<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\SettingsHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register blade directive for settings
        Blade::directive('setting', function ($expression) {
            return "<?php echo \App\Helpers\SettingsHelper::get($expression); ?>";
        });

        // Register blade directive for school name
        Blade::directive('schoolName', function () {
            return "<?php echo \App\Helpers\SettingsHelper::getSchoolName(); ?>";
        });

        // Register blade directive for school motto
        Blade::directive('schoolMotto', function () {
            return "<?php echo \App\Helpers\SettingsHelper::getSchoolMotto(); ?>";
        });
    }
}
