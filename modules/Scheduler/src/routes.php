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
        'auth'
    ],
    
    'namespace' => 'Modules\Scheduler\Controller'
], function () {
    
//    Route::get('/', 'SchedulerController@test');
    Route::get('/home', 'CustomController@index');
    Route::get('/schedulerHome', 'SchedulerController@showHomePage');
    Route::post('/add_scheduler', 'SchedulerController@AddDailyScheduler'); 
    
    //route to edit scheduler.
    Route::post('/edit_scheduler', 'SchedulerController@EditScheduler'); 
    
    Route::post('get_template', 'SchedulerController@GetTemplate');    
    //Route for delete scheduler.
    Route::post('/deleteScheduler', 'SchedulerController@DeleteScheduler');
    
    //Route for edit shceduler.
    Route::post('/getSchedulerDetails', 'SchedulerController@getSchedulerDetailsBySchedulerId'); 
    
});

Route::group([
    'middleware' => [
        'web',
        'guest'
    ],
    
    'namespace' => 'Modules\Scheduler\Controller'
], function () {
    
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::get('/login', 'Auth\LoginController@showLoginForm');
    Route::get('/register', 'Auth\RegisterController@showRegistrationForm');
    //Route::get('/login', 'Auth\LoginController@login');
//    Route::get('/schedulerHome', 'SchedulerController@showHomePage');
        
});

