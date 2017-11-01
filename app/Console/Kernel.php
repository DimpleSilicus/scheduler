<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Illuminate\Support\Facades\Storage;

use App\Events;
use App\Events\Event;
use App\Events\ActionDone;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
            '\App\Console\Commands\DailyScheduler',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        echo 'here---------------------------';       
           
        //Commented for now.
        $DailyResult=DB::table('scheduler as s')
            ->get()
            ->toArray();
//        print_r($DailyResult);
        $count=count($DailyResult);
        for($i=0;$i<$count;$i++)
        {
            if($DailyResult[$i]->interval == 'daily') 
            {
                 $schedule->command('DailyScheduler:dailyschedule')
                 ->everyMinute()
                ->after(function() {
                    // Task is complete...
                        event(new ActionDone(1));                          
               });
            }
        }
        
       
//        print_r($res);
//                ->dailyAt('13:00');
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
