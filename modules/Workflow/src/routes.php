<?php

/**
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 *
 * @name       routes
 * @category   Module
 * @package    Workflow
 * @author     Dimple Agarwal<dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    1
 * @link       None
 * @filesource
 */

Route::group([
    'middleware' => [
        'web',
        'guest'
    ],
    
    'namespace' => 'Modules\Workflow\Controller'
], function () {
    
//    Route::get('/cronjob', 'FederalController@cronjob');
});

Route::group([
    'middleware' => [
        'web',
        'auth'
    ],
    
    'namespace' => 'Modules\Workflow\Controller'
], function () {
    
//    Route::get('/', 'SchedulerController@test');
//    Route::get('/schedulerHome', 'SchedulerController@showHomePage');
    
    Route::get('/properties','VacationPropertyController@index');
    Route::post('/property/create','VacationPropertyController@createNewProperty');
    Route::get(
    '/property/new',
    [     
     function() {
         return response()->view('Workflow::property.newProperty');
     }]);
    Route::get(
    '/property/{id}',
    ['as' => 'property-show',
//     'middleware' => 'auth',
     'uses' => 'VacationPropertyController@show']);
    
    // Reservation related routes
    Route::post('/property/{id}/reservation/create','WorkflowController@create');
    
    Route::get(
    '/property/{id}/edit','VacationPropertyController@editForm');
    
    Route::post(
    '/property/edit/{id}','VacationPropertyController@editProperty');
});

//Home related routes
Route::get(
    '/workflow', ['as' => 'home', function () {
//    return 'Hello World';
        return response()->view('Workflow::home1');
    }]
);

/*
// Session related routes
Route::get(
    '/auth/login', ['as' => 'login-index', function() {
        return response()->view('login');
    }]
);

Route::get(
    '/login', ['as' => 'login-index', function() {
        return response()->view('login');
    }]
);

Route::post(
    '/login',
    ['uses' => 'SessionController@login', 'as' => 'login-action']
);

Route::get(
    '/logout', ['as' => 'logout', function() {
        Auth::logout();
        return redirect()->route('home');
    }]
);
*/
// User related routes
Route::get(
    '/user/new', ['as' => 'user-new', function() {
        return response()->view('newUser');
    }]
);

Route::post(
    '/user/create',
    ['uses' => 'UserController@createNewUser', 'as' => 'user-create', ]
);

// Vacation Property related routes


//Route::get(
//    '/properties',
//    ['as' => 'property-index',
//     'middleware' => 'auth',
//     'uses' => 'VacationPropertyController@index']
//);








//Route::post(
//    '/property/create',
//    ['uses' => 'VacationPropertyController@createNewProperty',
//     'middleware' => 'auth',
//     'as' => 'property-create']
//);

// Reservation related routes


Route::post(
    '/reservation/incoming',
    ['uses' => 'ReservationController@acceptReject',
     'as' => 'reservation-incoming']
);


