<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* Get and make css cache enabled  */
        $cssCacheEnabled = config('app.css_cache_enabled');
        view()->share('cssCacheEnabled', $cssCacheEnabled);
        
         /* Get and make js cache enabled  */
        $jsCacheEnabled = config('app.js_cache_enabled');
        view()->share('jsCacheEnabled', $jsCacheEnabled);

        /* Set time stamp for JS  */
        view()->share('jsTimeStamp', $jsCacheEnabled ? '?t=' . time() : '');
        
        /* Set time stamp for CSS */
        view()->share('cssTimeStamp', $cssCacheEnabled ? '?t=' . time() : '');
        
        /* Get and make global CMS theme */
        $theme = config('app.theme');
        view()->share('theme', $theme);
        
        Schema::defaultStringLength(191);
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
