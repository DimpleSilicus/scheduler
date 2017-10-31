<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Events;

use App\Events\SendMail;

class DailyScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DailyScheduler:dailyschedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Daily Scheduler';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        DB::table('scheduler')->delete(12);
        $DailyResult=DB::table('scheduler as s')->join('daily_scheduler as ds', 'ds.s_id', '=', 's.id')
//            ->where('s.user_id', '=', $userId)
            ->get()
            ->toArray();
        print_r($DailyResult);
        echo $count=count($DailyResult);
        for($i=0;$i<=$count;$i++)
        {
            echo $DailyResult[$i]->interval;
            if($DailyResult[$i]->interval == 'daily') {                
//                echo "sd";
//                Event::fire(new ActionDone(1));
                Event::fire(new SendMail(1));
                
            }
                
        }
    }
}
