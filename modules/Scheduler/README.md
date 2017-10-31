ActivityLog
===========

A simple and clean Laravel 5.2 activity logger for monitoring user activity on a website or web application.

# Table of Contents
* [Team Members](#team-members)
* [Requirements](#requirements)
* [Getting Started](#getting-started)
* [Documentation](#documentation)

# <a name="team-members"></a>Team Members

* Vivek Bansal (vivek.bansal@silicus.com)

# <a name="requirements"></a>Requirements

* This package requires following things
* PHP 5.5+
* MySQL 5.5+
* Laravel 5.2
* MySQL 5.5+
* Laravel 5.2 scaffold Authentication using php artisan make:auth command.

# <a name="getting-started"></a>Getting Started

To install ActivityLog, make sure "modules/activitylog" has been added to Laravel 5's `composer.json` file.

	"psr-4": {
            "Modules\\ActivityLog\\": "modules/ActivityLog/src/"
        }

Then run `php composer update` from the command line. Composer will install the ActivityLog package. Now, all you have to do is register the service provider . In `config/app.php`, add this to the `providers` array:

	Modules\ActivityLog\ActivitylogServiceProvider::class

**Publishing migrations and configuration:**

To publish this package's configuration and migrations, run this from the command line:

	php artisan vendor:publish

> **Note:** Migrations are only published; remember to run them when ready.

To run migration to create activity_log table, run this from the command line:

	php artisan migrate

**Logging user activity:**

write below code in your controller functions:

	 $fillableData = [
            'controller'  => 'calender',
            'action'      => 'insert_action',
            'module'      => 'test_content_type',
            'description' => 'Testing_log_description',
        ];
        Activitylog::insertLog($fillableData);

##Saving log in database or file.

**Write below code in config/app.php:

	/*
      |--------------------------------------------------------------------------
      | Users activity log Configuration
      |--------------------------------------------------------------------------
      |
      | Here you may configure the users activity log for your application.
      | Available Settings: database and file
      |
     */
    'storeActivity'   => env('STORE_ACTIVITY', 'database'),

**For saving logs in database or file you have to write below code in .env file: STORE_ACTIVITY=database (for saving activity log in database)
STORE_ACTIVITY=file		(for saving activity log in file)

**Your logs will be saved in storage/logs/activity/activity_currentdate.log (activity_2016-07-01.log) file. For this you have to create activity folder in storage/logs folder.

**You can see logs now for that follow below steps:
Make url in routes .php file
call view function in ActivitylogController.php and hit url.

The above code will log an activity for the currently logged in user. The IP address and User Agent will automatically be saved. Environment will automatically saved from .env file.


# <a name="documentation"></a>Documentation
1. This module will provide you to see logs of users.
2. There is a dropdown at top to select log duration
    - Todays (Default)
    - 1 week
    - 1 Month
    - 2 Month
    - 3 Month