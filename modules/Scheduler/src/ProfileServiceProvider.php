<?php

/**
 * ProfileServiceProvider service provider for publish images, css and js.
 *
 * @name       ProfileServiceProvider
 * @category   Module
 * @package    Profile
 * @author     Amol Savat <amol.savat@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    GIT: $Id$
 * @link       None
 * @filesource
 */
namespace Modules\Profile;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

/**
 * ProfileServiceProvider for publish images, css, js
 *
 * @name ProfileServiceProvider
 * @category ServiceProvider
 * @package Activitylog
 * @author Amol Savat <amol.savat@silicus.com>
 * @license Silicus http://www.silicus.com
 * @version Release:<v.1>
 * @link None
 */
class ProfileServiceProvider extends ServiceProvider
{

    /**
     * For register our service provider
     *
     * @name register
     * @access public
     * @author Amol Savat <amol.savat@silicus.com>
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Profile', function ($app) {
            return new Profile();
        });
    }

    /**
     * This function is for send migration files in migration folder
     *
     * @name boot
     * @access public
     * @author Amol Savat <amol.savat@silicus.com>
     *
     * @return void
     */
    public function boot()
    {
        // get theme name
        $theme = Config::get('app.theme');
        
        // set theme path
        $this->loadViewsFrom(__DIR__ . '/Views/', 'Profile');
        $sourceMigration = realpath(__DIR__ . '/../migrations');
        // $this->publishes([$sourceMigration => database_path('migrations')]);
        $this->loadMigrationsFrom($sourceMigration);
        // For js
        $sourceJs = realpath(__DIR__ . '/../public/js');
        
        $this->publishes([
            $sourceJs => base_path('public/theme/' . $theme . '/assets/profile/js/')
        ]);
        
        // include route file of this package
        include __DIR__ . '/routes.php';
    }
}
