<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Product;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router)
    {
         // Đăng ký alias cho middleware
    $router->aliasMiddleware('admin', IsAdmin::class);
    $router->aliasMiddleware('auth', Authenticate::class);
    
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
