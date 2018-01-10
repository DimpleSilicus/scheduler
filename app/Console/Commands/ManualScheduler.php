<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class ManualScheduler extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ManualScheduler:manualScheduler {schedulerName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Manual Scheduler';

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
    public function handle() {
//        Log::info('Showing user profile for user'.$this->argument('templateId'));

        $schedulerName = $this->argument('schedulerName');
        $schedulerResult = DB::table('scheduler as s')
                ->where('s.name', '=', $schedulerName)
                ->get()
                ->toArray();        
        if ($schedulerResult[0]->type == 'email') {
             $TemplateResult=DB::table('email_template as et')
                      ->where('et.id','=',$schedulerResult[0]->template_id)
                      ->get()
                      ->toArray();
             
            $userResult = DB::table('users as u')
                            ->get()
                            ->toArray();            
            for ($i = 0; $i < count($userResult); $i++) {                
                        //This function is used to fire event.
                event(new SendMail($userResult[$i]->id, $TemplateResult[0]->path));
            }
        }
    }
}
