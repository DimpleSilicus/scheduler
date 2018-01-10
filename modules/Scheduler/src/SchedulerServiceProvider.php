<?php

/**
 * SchedulerServiceProvider service provider for publish images, css and js.
 *
 * @name       SchedulerServiceProvider
 * @category   Module
 * @package    Scheduler
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @link       None
 * @filesource
 */
namespace Modules\Scheduler;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

/**
 * SchedulerServiceProvider for publish images, css, js
 *
 * @name SchedulerServiceProvider
 * @category ServiceProvider
 * @package Activitylog
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://www.silicus.com
 * @version Release:<v.1>
 * @link None
 */
class SchedulerServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/Views/', 'Scheduler');
        $sourceMigration = realpath(__DIR__ . '/../migrations');
        // $this->publishes([$sourceMigration => database_path('migrations')]);
        $this->loadMigrationsFrom($sourceMigration);
        // For js
        realpath(__DIR__ . '/../public/js');    
        
        
        // include route file of this package
        include __DIR__ . '/routes.php';
    }
}
