<?php

/**
 * Routes is using for urls and namespace used for give path for controller
 *
 * @name       routes
 * @category   Module
 * @package    Naics
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
    
    'namespace' => 'Modules\Federal\Controller'
], function () {
    
    Route::get('/cronjob', 'FederalController@cronjob');
});

