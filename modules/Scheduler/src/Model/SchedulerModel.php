<?php
/**
 * Scheduler class to add / edit / delete user scheduler details.
 *
 * @name       SchedulerModel.php
 * @category   Scheduler
 * @package    Scheduler
 * @author     Dimple Agarwal<dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version
 * @link       None
 * @filesource
 */
namespace Modules\Scheduler\Model;

use Illuminate\Database\Eloquent\Model;
use Modules\Scheduler\Model\DailyScheduler;
use Modules\Scheduler\Model\WeeklyScheduler;
//use Modules\Scheduler\Model\EmailTemplate;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;
/**
 * Scheduler class for scheduler functionality like inserting scheduler data in master table of scheduler.
 * get all daily scheduler details, delete scheduler by scheduler id
 *
 * @category Scheduler
 * @package Scheduler
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name Scheduler
 * @version 
 * @link http://google.com
 */
class Scheduler extends Model
{
    use SoftDeletes;
    
    protected $primaryKey = 'id';

    protected $table = 'scheduler';

    public $timestamps = false;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * Function to add scheduler details.
     *
     * @name InsertSchedulerDetails
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     *
     * @return array
     */   
    
    public static function InsertSchedulerDetails($userId,$schedulerDetail)
    {
        $objMember = new self();                 
        
        $objMember->name = $schedulerDetail['schedulerName'];
        $objMember->type = $schedulerDetail['schedulerType'];  //email or batch
        $objMember->interval = $schedulerDetail['schedulerInterval']; //(daily,weekly,monthly)
        $objMember->template_id = $schedulerDetail['scheduleTemplate'];
        $objMember->start_date = $schedulerDetail['schedulerFromDate']; 
        $objMember->end_date = $schedulerDetail['schedulerToDate'];         
        $objMember->user_id = $userId;       
        
        $success = $objMember->save();
        
//        print_r($schedulerDetail['schedulerDateMultiple']);
        $schedulerTimes=$schedulerDetail['schedulerDateMultiple'];
        $count=count($schedulerTimes);
        if($success)
        {
            if($schedulerDetail['schedulerInterval'] == 'daily')
            {
                for($i=0;$i<$count;$i++)
                {
//                    echo "time:".$schedulerDetail['schedulerDateMultiple'][$i];
                     $dailyResponse=DailyScheduler::InsertDailyScheduler($objMember->id,$schedulerTimes[$i]);
                }

            }
            //For Weekly
            else if($schedulerDetail['schedulerInterval'] == 'weekly')
            {
                $weeklyDay=$schedulerDetail['day'];                
                $schedulerData=array($schedulerTimes,$weeklyDay);
                $dailyResponse= WeeklyScheduler::InsertWeeklyScheduler($objMember->id,$schedulerData);
            }
        }
        return $dailyResponse;
    }
    
    /**
     * Function to get all daily scheduler details user wise.
     *
     * @name getAllDailySchedulerbyUserId
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     *
     * @return void
     */
    public static function getAllDailySchedulerbyUserId($userId) {        
        
        $AllScheduler = self::select('scheduler.name','scheduler.interval','scheduler.status','scheduler.id as sid',DB::raw('group_concat(time_of_day) as time_of_day'))
                            ->join('daily_scheduler as ds', 'ds.s_id', '=', 'scheduler.id')
                            ->where('scheduler.user_id', '=', $userId)
                            ->groupBy('scheduler.id')
                            ->get()
                            ->toArray();
        return $AllScheduler;
    }    
    
    /**
     * Function to delete scheduler.
     *
     * @name deleteSchedulerBySchedulerId
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function deleteSchedulerBySchedulerId($schedulerId) {
        
        $objSchedule = Scheduler::find($schedulerId);
        $success = $objSchedule->delete();        
        return $success;
    }
    
    /**
     * Function to get scheduler details by scheduler id.
     *
     * @name getSchedulerDetailsBySchedulerId
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return array
     */
    public static function getSchedulerById($schedulerId)
    {
         return Scheduler::select("email_template.name as temp_name", "scheduler.*")->join("email_template", "email_template.id", "=", "template_id")
                 ->where("scheduler.id", $schedulerId)
                 ->first()->toArray();
    }
    
    /**
     * Function to get update scheduler details by user id.
     *
     * @name UpdateSchedulerDetails
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     * @return boolean
     */
    public static function UpdateSchedulerDetails($userId, $schedulerDetail)
    {        
        $objMemberUpdate = Scheduler::find($schedulerDetail['schedulerId']);
                
        $objMemberUpdate->name = $schedulerDetail['schedulerName'];
        $objMemberUpdate->type = $schedulerDetail['schedulerType'];  //email or batch
        $objMemberUpdate->interval = $schedulerDetail['schedulerInterval']; //(daily,weekly,monthly)
//        $objMemberUpdate->template_id = $schedulerDetail['scheduleTemplate'];
        $objMemberUpdate->start_date = $schedulerDetail['schedulerFromDate']; 
        $objMemberUpdate->end_date = $schedulerDetail['schedulerToDate'];         
        $objMemberUpdate->user_id = $userId;  
        
        $success = $objMemberUpdate->save();
        
        $schedulerTimes=$schedulerDetail['schedulerDateMultiple'];
        $count=count($schedulerTimes);
        
        $schedulerTimesEdit=$schedulerDetail['schedulerDateMultipleEdit'];
        $arrDaily= DailyScheduler::getDailySchedulerBySchedulerId($schedulerDetail['schedulerId']);
        
        if($success)
        {
            if($schedulerDetail['schedulerInterval'] == 'daily')
            {
                //For new added time.
                if (is_array($schedulerTimes))
                {
//                    echo "inside";
                    for($i=0;$i<$count;$i++)
                    {
                        //InsertDailyScheduler
                         $dailyResponse=DailyScheduler::InsertDailyScheduler($schedulerDetail['schedulerId'],$schedulerTimes[$i]);                       
                    }
                }
                
                if (is_array($schedulerTimesEdit))
                {   
                    //For Edit time which is alredy present.
                    foreach ($arrDaily as $key => $value){                         
                        if(!in_array($key, array_keys($schedulerTimesEdit))) {
                            
                            $dailyResponse=DailyScheduler::DeleteDailyScheduler($key);  
                            
                        } else {                            
                            $dailyResponse=DailyScheduler::UpdateDailyScheduler($key,$schedulerTimesEdit[$key]);   
                        }                        
                    }
                }                
            }
            
        }
        echo $dailyResponse;
        return $dailyResponse;
    }
    
}
