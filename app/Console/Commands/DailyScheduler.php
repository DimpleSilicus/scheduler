<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Events;
use App\Events\Event;
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
            ->get()
            ->toArray();

        $count=count($DailyResult);
        for($i=0;$i<$count;$i++)
        {
            if($DailyResult[$i]->interval == 'daily') 
            {
                if($DailyResult[$i]->type == 'email')
                {
                    $TemplateResult=DB::table('scheduler as s')
                                    ->leftJoin('email_template as et', 'et.id', '=', 's.id')
                                    ->get()
                                    ->toArray();                    
                    //$output = new \Symfony\Component\Console\Output\ConsoleOutput(2);
                    //$output->writeln('in handle function');
                    //This function is used to fire event.
                    event(new SendMail($DailyResult[$i]->user_id,$TemplateResult[0]->path));                    
                }
            }                
        }        
       
    }   
    
}
