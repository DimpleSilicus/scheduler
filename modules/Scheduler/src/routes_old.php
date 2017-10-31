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
    
    'namespace' => 'Modules\Profile\Controller'
], function () {
    Route::get('profiles/mynetwork', 'ProfileController@getMyNetwork');
    // reqests realted to Request Recived section
    Route::post('profiles/searchPepole', 'ProfileController@searchPeopleByNameAjax');
    Route::post('profiles/addUserRequestToConnect', 'ProfileController@addUserRequestToConnect');
    Route::post('profiles/approveUserRequest', 'ProfileController@approveRejectUserRequest');
    Route::post('profiles/rejectUserRequest', 'ProfileController@approveRejectUserRequest');
    Route::post('profiles/listUserRequest', 'ProfileController@getUserRequestReceived');
    Route::post('profiles/deleteUserSuggestion', 'ProfileController@deleteSuggestion');
    Route::get('profiles/deleteSuggestList', 'ProfileController@DeleteSuggestionList');

    
    // request realted to Group/Forum
    Route::post('group/create', 'ProfileController@createUserGroup');
    Route::post('group/list', 'ProfileController@getUsersGroups');
    Route::post('group/details', 'ProfileController@getGroupDetailsByGroupId');
    Route::post('group/edit', 'ProfileController@editUserGroup');
    
    // routes realted to Messages
    Route::post('message/group/compose', 'ProfileController@createGroupMessage');
    Route::post('message/participant/compose', 'ProfileController@createParticipantMessage');
    Route::post('message/list', 'ProfileController@getUserMessages');
    
    // routes related to Privacy Settings
    
    Route::post('user/privacySettings', 'ProfileController@setUserPrivacySettings');
    Route::post('user/getPrivacySettings', 'ProfileController@getPrivacySettings');

    
    // routes related to Notifications
    Route::post('notifications/list', 'ProfileController@getUserNotification');
    Route::post('notifications/mark', 'ProfileController@markUserNotification');
    
    // route for user public profile
   

    Route::get('profiles/user-profile/{userid}', 'PublicProfileController@getUserProfile');
    Route::post('profiles/getJournalSettings', 'PublicProfileController@getJournalPrivacySettings');
    Route::post('profiles/getEventSettings', 'PublicProfileController@getEventsPrivacySettings');
    Route::post('profiles/getPictureSettings', 'PublicProfileController@getPicturePrivacySettings');
    Route::post('profiles/getVideoSettings', 'PublicProfileController@getVideoPrivacySettings');
    Route::post('profiles/getProfileRelation', 'PublicProfileController@getRelationWithProfile');
    Route::post('profiles/getProfilerInfo', 'PublicProfileController@getProfilerDetails');
    
});
