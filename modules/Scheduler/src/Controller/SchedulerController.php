<?php

/**
 *  Controller for viewing all users logs
 *
 * @name       ProfileController
 * @category   Plugin
 * @package    Profile
 * @author     Amol Savat <amol.savat@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    GIT: $Id$
 * @link       None
 * @filesource
 */
namespace Modules\Scheduler\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

use Modules\Scheduler\Model\Scheduler;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Modules\ToolKit\Workshop;

/**
 * SchedulerController class for view method.
 *
 * @category SchedulerController
 * @package Activity-Log
 * @author Amol Savat <amol.savat@silicus.com>
 * @license Silicus http://google.com
 * @name ProfileController
 * @version Release:<v.1>
 * @link http://google.com
 */
class SchedulerController extends Controller
{
     use AuthenticatesUsers;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        parent::__construct();
//        echo $this->url."js/scheduler.js";
        $jsFiles[]  = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js";
        $jsFiles[] = $this->url."js/scheduler.js";
//        $jsFiles[]  = "https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js";
//        $jsFiles[]  = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css";
        
        $jsFiles[]  = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js";
//        print_r($jsFiles);
        $cssFiles[] = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css";
        $this->loadJsCSS($jsFiles, $cssFiles);
    }
    
    /**
     * Showing Scheduler details.
     *
     * @name showHomePage
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
    
    public function showHomePage() {
//        dd('sdfsdf');
        return view('Scheduler::scheduler_home');
        
    }
    
    public function showMainPage()
    {
        return view($this->theme . '.auth.login');
    }
    
    public function AddDailyScheduler(Request $request)
    {
//        echo 'asdas'.Auth::id();
//        print_r($request->getContent());
        //schedulerName=sdfsd&schedulerType=email&schedulerInterval=daily&schedulerDate=09%2F26%2F2017
        
        $schedulerDate = date("Y-m-d H:i:s", strtotime($request->schedulerDate));
        $scheduleTime=date("H:i:s", strtotime($request->schedulerDate));
        $member = Scheduler::InsertSchedulerDetails(Auth::id(), $request->schedulerName, $request->schedulerType, $request->schedulerInterval, $schedulerDate,$scheduleTime);
        echo "mem::".$member;
    }
    
    public function getDailyScheduler()
    {
        $ResDaily=Scheduler::getAllDailySchedulerbyUserId(Auth::id());
        print_r($ResDaily);
        
        return view('Scheduler::scheduler_home')->with('resDaily', $ResDaily);
    }
    
}
