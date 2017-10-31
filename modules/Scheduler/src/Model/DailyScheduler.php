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

class DailyScheduler extends Model
{

    protected $primaryKey = 'id';

    protected $table = 'daily_scheduler';

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
    public static function InsertDailyScheduler($scheduleId,$scheduleTime)
    {       
        $objDaily = new self();   
        
        $objDaily->s_id = $scheduleId;
        $objDaily->time_of_day = $scheduleTime;  //email or batch
          
        
        $success = $objDaily->save();
        
       
        return $success;
    }

       
       
}
