<?php

/**
 *  Laravel auth controller class file
 *
 * @name       LoginController.php
 * @category   Controllers
 * @package    Auth
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    GIT: $Id: c8e7553654d1c055d0c5b734ea2d4c858ad92fe5 $
 * @link       None
 * @filesource
 */
namespace Modules\Scheduler\Controller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * Registration & Login Controller
 *
 * This controller handles the registration of new users, as well as the
 * authentication of existing users. By default, this controller uses
 * a simple trait to add these behaviors. Why don't you explore it?
 *
 * @name LoginController
 * @category Controller
 * @package Auth
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://www.silicus.com/
 * @version Release:<v.1>
 * @link None
 */
class LoginController extends Controller
{
    /*
     * |--------------------------------------------------------------------------
     * | Login Controller
     * |--------------------------------------------------------------------------
     * |
     * | This controller handles authenticating users for the application and
     * | redirecting them to your home screen. The controller uses a trait
     * | to conveniently provide its functionality to your applications.
     * |
     */
    
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $redirectAfterLogout = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        /*$this->middleware('guest', [
            'except' => [
                'logout',
                'getLogout'
            ]
        ]);*/
    }

    /**
     * Display login form
     *
     * @name showLoginForm
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
    public function showLoginForm()
    {
        
        
        return view($this->theme . '.auth.login');
    }

    /**
     * Redirect user after login
     *
     * @name authenticated
     * @access protected
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @param Array $request
     *            Request
     * @param Object $user
     *            User object
     *
     * @return void
     */
    protected function authenticated($request, $user)
    {
		
        /*if ($user->isAdmin() == true) {
            return redirect()->intended('/admin/dashboard');
        }*/
		
		
		if ($user->is_admin==1) {
            return redirect()->intended('/admin/dashboard');
        }
        
        return redirect()->intended($this->redirectTo);
    }
    
    /**
        * Get the needed authorization credentials from the request.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return array
    */
       protected function credentials(Request $request)
       {
//           echo "sdf::".$request->{$this->username()};
           return [
               'username' => $request->{$this->username()},
               'password' => $request->password,
               'status' => '1',
           ];
       }

    public function username()
    {
        return 'username';
    }

    public function showMainPage()
    {
        return view($this->theme . '.auth.main');
    }

    public function showContactUsPage()
    {
        return view($this->theme . '.auth.contact-us');
    }

    public function showServicesPage()
    {
        return view($this->theme . '.auth.services');
    }

    public function showAboutAppPage()
    {
        return view($this->theme . '.auth.about-app');
    }

    public function showFamilyHistoryPage()
    {
        return view($this->theme . '.auth.family-history');
    }

    /**
     * Admin logout page
     *
     * @name adminLogout
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return void
     */
    public function adminLogout()
    {
        \Auth::logout();
        return \Redirect::to('/');
    }
}
