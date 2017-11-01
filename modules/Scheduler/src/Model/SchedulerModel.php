<?php
/**
 * Mailbox class to add / edit / delete user privacy settings
 *
 * @name       UserPrivacy.php
 * @category   UserPrivacy
 * @package    Profile
 * @author     Amol Savat <amol.savat@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version
 * @link       None
 * @filesource
 */
namespace Modules\Scheduler\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Modules\Scheduler\Model\DailyScheduler;

class Scheduler extends Model
{

    protected $primaryKey = 'id';

    protected $table = 'scheduler';

    public $timestamps = false;

    /**
     * Function to add user privacy settings
     *
     * @name InsertSchedulerDetails
     * @access public
     * @author Dimple Agarwal<dimple.agarwal@silicus.com>
     *
     * @return void
     */
    
    //Scheduler::InsertSchedulerDetails(Auth::id(), $request->schedulerName, $request->schedulerType, $request->schedulerInterval, $request->schedulerDate);
    public static function InsertSchedulerDetails($userId,$schedulerName,$schedulerType,$schedulerInterval,$scheduleTemplate,$schedulerDate,$scheduleTime)
    {
        $objMember = new self();       
        $objDaily = new self();   
        
        $objMember->name = $schedulerName;
        $objMember->type = $schedulerType;  //email or batch
        $objMember->interval = $schedulerInterval; //(daily,weekly,monthly)
        $objMember->template_id = $scheduleTemplate;
        $objMember->start_date = $schedulerDate; 
        $objMember->end_date = $schedulerDate;         
        $objMember->user_id = $userId;       
        
        $success = $objMember->save();
        
        if($schedulerInterval == 'daily')
        {
            echo "daily";
//            $objDaily->name = $objMember->id;
            $dailyResponse=DailyScheduler::InsertDailyScheduler($objMember->id,$scheduleTime);                       
        }
        return $dailyResponse;
    }
    
    public static function getAllDailySchedulerbyUserId($userId) {
        //SELECT * FROM `scheduler` as s join daily_scheduler as ds on ds.s_id=s.id WHERE s.user_id=1
        
        $mem = self::select('*')->join('daily_scheduler', 'ds.s_id', '=', 's.id')
            ->where('s.user_id', '=', $userId)
            ->get()
            ->toArray();
        
        dd(DB::getQueryLog());
        return $mem;
    }

       
       
}
