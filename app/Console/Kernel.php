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
        $DailyResult=DB::table('scheduler as s')    
                ->select('*', 's.id as sid')
                ->join('daily_scheduler as ds', 'ds.s_id', '=', 's.id')
                ->where('s.status','=','1')
                ->get()
                ->toArray();
        $count=count($DailyResult);
        for($i=0;$i<$count;$i++)
        {
            $finalResDaily=$DailyResult[$i];
            if($DailyResult[$i]->interval == 'daily') 
            {
                 $schedule->command('DailyScheduler:dailyschedule '.$DailyResult[$i]->interval.' '.$DailyResult[$i]->type.' '.$DailyResult[$i]->user_id.' '. $DailyResult[$i]->template_id)
                 ->everyMinute()
                ->after(function() use ($finalResDaily) {
                    // Task is complete...
                        event(new ActionDone($finalResDaily->sid,1));                          
               });
               //For running scheduler on daily basis.
//                ->dailyAt($DailyResult[$i]->time_of_day);
            }
        }
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
