<?php

/**
 * FederalServiceProvider service provider for publish images, css and js.
 *
 * @name       FederalServiceProvider
 * @category   Module
 * @package    Federal
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @link       None
 * @filesource
 */
namespace Modules\Federal;

use Illuminate\Support\ServiceProvider;

/**
 * FederalServiceProvider for publish images, css, js
 *
 * @name FederalServiceProvider
 * @category ServiceProvider
 * @package Naics
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://www.silicus.com
 * @version Release:<v.1>
 * @link None
 */
class FederalServiceProvider extends ServiceProvider
{    

    /**
     * This function is for send migration files in migration folder
     *
     * @name boot
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
    public function boot()
    {                
        // set theme path
        $this->loadViewsFrom(__DIR__ . '/Views/', 'Federal');
        $sourceMigration = realpath(__DIR__ . '/../migrations');
//         $this->publishes([$sourceMigration => database_path('migrations')]);
        $this->loadMigrationsFrom($sourceMigration);
//        // For js
//        realpath(__DIR__ . '/../public/js');    
        
        
        // include route file of this package
        require base_path('modules\Federal\src\routes.php');

    }
}
