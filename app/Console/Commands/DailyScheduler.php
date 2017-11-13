<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Events;
use App\Events\Event;
use App\Events\SendMail;

use Illuminate\Support\Facades\Log;

class DailyScheduler extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DailyScheduler:dailyschedule {interval} {type} {userId} {templateId}';

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
    public function __construct() {        
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {                
//        Log::info('Showing user profile for user'.$this->argument('templateId'));
        $interval=$this->argument('interval');
        $type=$this->argument('type');
        $user_id=$this->argument('userId');
        $templateId=$this->argument('templateId');
        
//        $count=count($DailyResult);
//        for($i=0;$i<$count;$i++)
//        {
            if($interval == 'daily') 
            {
                if($type == 'email')
                {
                    $TemplateResult=DB::table('scheduler as s')
                                    ->leftJoin('email_template as et', 'et.id', '=', 's.template_id')
                                    ->where('s.template_id','=',$templateId)
                                    ->get()
                                    ->toArray();
                    //This function is used to fire event.
                    event(new SendMail($user_id,$TemplateResult[0]->path));                    
                }
            }                
//        }        
       
    }   
    

}
