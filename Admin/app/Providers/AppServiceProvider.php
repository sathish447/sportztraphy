<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Guard $guard)
    {
        // View::composer('*', function($view) use($guard) {

        //     $adminTicketsCount = Supportchat::on('mysql2')->where('admin_status', 0)->count();

        //     $view->with('adminTicketsCount', $adminTicketsCount);

        //  });
        Schema::defaultStringLength(191);
        Blade::directive('convert', function ($money) {
            return "<?php echo number_format($money, 2); ?>";
        });
}

/**
* Register any application services.
*
* @return void
*/
public function register()
{
//
}
}