<?php
/**
 *  NaicsController for getting naics data through api.
 *
 * @name       NaicsController
 * @category   Plugin
 * @package    Naics
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    none
 * @link       None
 * @filesource
 */
namespace Modules\Naics\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\Naics\Model\Encrypt;
/**
 * NaicsController class for Naics operations. 
 *
 * @category NaicsController
 * @package Naics
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name NaicsController
 * @version Release:<v.1>
 * @link http://google.com
 */
class NaicsController extends Controller
{
    /**
    * Create a new controller instance.
    * Use to load JS and CSS files.
    * @return void
    */
    public function __construct()
    {        
        parent::__construct();        
//        C:\wamp64\www\scheduler\public\naics\js\naics.js        
        $jsFiles[] = $this->url."naics/js/naics.js";    
        $this->loadJsCSS($jsFiles);
    }
    
    /**
     * Getting naics API Data.
     *
     * @name naicsData
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
  
    public function naicsData(Request $request) {

        $NaicsCode=$request->NaicsCode;
        $year=2012;
//        $year= date('Y');
        $messages = [
            'NaicsCode.required' => 'NAICS Code is required.',                             
        ];
        
        $this->validate($request, [
            'NaicsCode' => 'required|numeric'            
        
        ], $messages);
        
        $clientnew = new \GuzzleHttp\Client(['verify' => false,'cookies' => true]);
        $response=$clientnew->get(
            'http://api.naics.us/v0/q?year='.$year.'&code='.$NaicsCode            
        );        
        
        $statusCode=$response->getStatusCode();
        if ($statusCode == 200) {
            echo $content = $response->getBody()->getContents();
            if ($content) {
                \Session::flash('success', 'Data retrieve successfully.');
            }
            else
            {
                \Session::flash('danger', 'Something went wrong');
            }
//            echo "Return True-".$statusCode;
        }
        else
        {
            return false;
//            echo "Return False-".$statusCode;
        }
        
    }
   
    
    
    public function naicsHome()
    {
        return view('Naics::naics_home');
//                ->with('allSchedulerResult', $allSchedulerResult);
    }
    
    public function randomEncryt()
    {
        echo "randomEncryt";
        Encrypt::randomEncryt();
                
    }
}
