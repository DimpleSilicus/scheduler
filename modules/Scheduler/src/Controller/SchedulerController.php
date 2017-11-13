<?php
/**
 *  Controller for viewing all scheduler details(showing scheduler,delete).
 *
 * @name       GedcomController
 * @category   Plugin
 * @package    Scheduler
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    none
 * @link       None
 * @filesource
 */
namespace Modules\Scheduler\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Scheduler\Model\Scheduler;
use Modules\Scheduler\Model\DailyScheduler;
use Modules\Scheduler\Model\EmailTemplate;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Carbon\Carbon;
/**
 * SchedulerController class for scheduler functionality like showing scheduler home page, login page, get template details,
 * add scheduler data on add scheduler form submit.
 *
 * @category SchedulerController
 * @package Scheduler
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name SchedulerController
 * @version Release:<v.1>
 * @link http://google.com
 */
class SchedulerController extends Controller
{
     use AuthenticatesUsers;
    /**
     * Create a new controller instance.
     * Use to load JS and CSS files.
     * @return void
     */
    public function __construct()
    {        
        parent::__construct();
        
        $jsFiles[]  = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js";
        $jsFiles[] = $this->url."js/scheduler.js";
        $jsFiles[] = $this->url."js/jquery.validate.min.js";
        $jsFiles[] = "http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js";
        
        $jsFiles[] = $this->url."js/additional-methods.js";
        
        $jsFiles[]  = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js";
        $cssFiles[] = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css";
        $this->loadJsCSS($jsFiles, $cssFiles);
    }
    
    /**
     * Showing Scheduler details Page(Home page) which displays scheduler details and add scheduler button.
     *
     * @name showHomePage
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
  
    public function showHomePage() {

        $allSchedulerResult=Scheduler::getAllDailySchedulerbyUserId(Auth::id());
        
        return view('Scheduler::scheduler_home')->with('allSchedulerResult', $allSchedulerResult);
        
    }
    
    public function showMainPage()
    {
        return view($this->theme . '.auth.login');
    }
    
    /**
     * Insert Daily Scheduler Data in Database and also in daily scheduler table.
     *
     * @name AddDailyScheduler
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
    
    public function AddDailyScheduler(Request $request)
    {        
//        dd($request);
        //schedulerFromDate schedulerToDate
        // validate inputs
        $messages = [
            'schedulerName.required' => 'Scheduler Name is required.',
            'schedulerType.required' => 'Scheduler Type is required.',
            'schedulerInterval.required' => 'Scheduler Interval is required.',
            'scheduleTemplate.required' => 'Scheduler Template is required.',
//            'schedulerDateMultiple.*.profile'=>[
//                'required' => 'Scheduler Time is required.',
//                ]
            
//            'schedulerDate.required' => 'Scheduler Date is required.'            
        ];
        
        $this->validate($request, [
            'schedulerName' => 'required|max:255',
            'schedulerType' => 'required',
            'schedulerInterval' => 'required',
            'scheduleTemplate' => 'required',
//            'schedulerDateMultiple.profile'=>'required'
//            'schedulerFromDate' => 'date|after:tomorrow|date_format:Y-m-d',
        
        ], $messages);
        
        //For From Date check.
        if($request->schedulerFromDate != '')
        {
            $schedulerFromDate=$request->schedulerFromDate;
        }
        else
        {
            $schedulerFromDate=date('Y-m-d');
        }
        
        //For To Date check.
        if($request->schedulerToDate != '')
        {
            $schedulerToDate=$request->schedulerToDate;
        }
        else
        {
            $schedulerToDate=date('Y-m-d');
        }
        
        
//        echo "date:".Carbon::createFromFormat('Y-m-d H:i:s', $request->schedulerDate)->toDateTimeString();
//        $schedulerDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->schedulerDate)->toDateTimeString();
//        $scheduleTime=date("H:i:s", strtotime($request->schedulerDate));        
        $schedulerDetail['schedulerId']=$request->schedulerId;
        $schedulerDetail['schedulerName']=$request->schedulerName;
        $schedulerDetail['schedulerType']=$request->schedulerType;
        
        $schedulerDetail['schedulerFromDate']=$schedulerFromDate;
        $schedulerDetail['schedulerToDate']=$schedulerToDate;
        
        $schedulerDetail['schedulerDateMultiple']= $request->schedulerDateMultiple;
        
        $schedulerDetail['schedulerInterval']=$request->schedulerInterval;
        $schedulerDetail['scheduleTemplate']=$request->scheduleTemplate;
        
        if($request->schedulerInterval == 'weekly')
        {
            $schedulerDetail['day']=$request->day;
        }

        
        $member = Scheduler::InsertSchedulerDetails(Auth::id(),$schedulerDetail);
                
        echo $member;
        if ($member) {
            \Session::flash('success', 'Scheduler added successfully.');
        }
        else
        {
            \Session::flash('danger', 'Please try again letter');
        }
    }
    
    /**
     * Get email template Details for showing available template in database.
     *
     * @name GetTemplate
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return array
     */
    public function GetTemplate() {
        
        $res=EmailTemplate::GetTemplate();              
        return $res;
    }
    
    /**
     * Function is to Delete Scheduler(Soft Delete).
     *
     * @name DeleteScheduler
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
    public function DeleteScheduler(Request $request) {
//        echo "DeleteScheduler".$request->schedulerId;
        //Return boolean value.
        $delete = Scheduler::deleteSchedulerBySchedulerId($request->schedulerId);
        
        if ($delete) {
            \Session::flash('success', 'Scheduler deleted successfully.');
        }
        else
        {
            \Session::flash('danger', 'Please try again later');
        }
    }
    
    /**
     * Function is to get scheduler details by scheduler id. used while getting data at time of edit scheduler.
     *
     * @name getSchedulerDetailsBySchedulerId
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
    public function getSchedulerDetailsBySchedulerId(Request $request) {
        // validate inputs
        $messages = [
            'schedulerId.required' => 'Scheduler ID is required.'
        ];
        
        $this->validate($request, [
            'schedulerId' => 'required'
        
        ], $messages);
        
        $schDetails['schedular'] = Scheduler::getSchedulerById($request->schedulerId);
        $schDetails['daily_schedular'] = DailyScheduler::getDailySchedulerBySchedulerId($request->schedulerId);
        
        return response()->json($schDetails);
    }
    
    
    public function EditScheduler(Request $request) {
        
//        dd($request->schedulerDateMultiple);
        

        // validate inputs
        $messages = [
            'schedulerNameU.required' => 'Scheduler Name is required.',
            'schedulerTypeU.required' => 'Scheduler Type is required.',
            'schedulerIntervalU.required' => 'Scheduler Interval is required.',
//            'scheduleTemplateU.required' => 'Scheduler Template is required.', //Commented for now
//            'schedulerDateU.required' => 'Scheduler Date is required.'            
        ];
        
        $this->validate($request, [
            'schedulerNameU' => 'required|max:255',
            'schedulerTypeU' => 'required',
            'schedulerIntervalU' => 'required',
//            'scheduleTemplateU' => 'required',
//            'schedulerFromDate' => 'date|after:tomorrow',
        
        ], $messages);
        
        //For From Date check.
        if($request->schedulerFromDate != '')
        {
            $schedulerFromDate=$request->schedulerFromDate;
        }
        else
        {
            $schedulerFromDate=date('Y-m-d');
        }
        
        //For To Date check.
        if($request->schedulerToDate != '')
        {
            $schedulerToDate=$request->schedulerToDate;
        }
        else
        {
            $schedulerToDate=date('Y-m-d');
        }
//        print_r(array_filter($request->schedulerDateMultiple));
//        $schedulerDate = date("Y-m-d H:i:s", strtotime($request->schedulerDateU));
//        $scheduleTime=date("H:i:s", strtotime($request->schedulerDateU));        
        $schedulerDetail['schedulerId']=$request->schedulerId;
        $schedulerDetail['schedulerName']=$request->schedulerNameU;
        $schedulerDetail['schedulerType']=$request->schedulerTypeU;
        
        $schedulerDetail['schedulerFromDate']=$schedulerFromDate;
        $schedulerDetail['schedulerToDate']=$schedulerToDate;        
        $schedulerDetail['schedulerDateMultiple']= $request->schedulerDateMultiple;
        $schedulerDetail['schedulerDateMultipleEdit']= array_filter($request->schedulerDateMultipleEdit);
        
        $schedulerDetail['schedulerInterval']=$request->schedulerIntervalU;
//        $schedulerDetail['scheduleTemplate']=$request->scheduleTemplate;
//        $schedulerDetail['schedulerDate']=$schedulerDate;
//        $schedulerDetail['scheduleTime']=$scheduleTime;
        
        // update picture details
        echo $update = Scheduler::UpdateSchedulerDetails(Auth::id(),$schedulerDetail);
        
        if ($update) {
            \Session::flash('success', 'Scheduler Updated successfully.');
        }
    }
    
    public function test()
    {
//        return "hello there";
        return view('Scheduler::test');
    }
}
