<?php

/**
 * Routes is using for urls and namespace used for give path for controller
 *
 * @name       routes
 * @category   Module
 * @package    ActivityLog
 * @author     Vivek Bansal <vivek.bansal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    GIT: $Id$mynetwork
 * @link       None
 * @filesource
 */

Route::group([
    'middleware' => [
        'web',
        'guest'
    ],
    'namespace' => 'Modules\Scheduler\Controller'
], function () {
    // Route::get('/login', 'Auth\LoginController@showLoginForm');
    
    
    //Route::get('/', 'SchedulerController@showMainPage');
    //Route::post('/login', 'Auth\LoginController@login');    
        
    
    
});
Route::group([
    'middleware' => [
        'web',
        'auth'
    ],
    
    'namespace' => 'Modules\Scheduler\Controller'
], function () {
    
//    Route::get('home', 'SchedulerController@showHomePage');
    Route::get('/schedulerHome', 'SchedulerController@showHomePage');
    Route::post('/add_scheduler', 'SchedulerController@AddDailyScheduler');
});
