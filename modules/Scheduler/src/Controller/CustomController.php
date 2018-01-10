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
class CustomController extends Controller
{     
    /**
     * Create a new controller instance.
     * Use to load JS and CSS files.
     * @return void
     */
    public function __construct()
    {        
        parent::__construct();        
        
        
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dd('asdas');        
        return view($this->theme . '.home');
//        return view('home');
    }
    
        
}
