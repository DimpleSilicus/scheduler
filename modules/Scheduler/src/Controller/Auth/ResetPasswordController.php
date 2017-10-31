<?php

/**
 *  Reset Password Controller
 *
 * @name       ResetPasswordController.php
 * @category   Controllers
 * @package    Auth
 * @author     Ajay Bhosale <ajay.bhosale@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    GIT: $Id: c8e7553654d1c055d0c5b734ea2d4c858ad92fe5 $
 * @link       None
 * @filesource
 */

namespace Modules\User\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

/**
 * Reset Password Controller
 *
 * This controller handles the registration of new users, as well as the
 * authentication of existing users. By default, this controller uses
 * a simple trait to add these behaviors. Why don't you explore it?
 *
 * @name     ResetPasswordController
 * @category Controller
 * @package  Auth
 * @author   Ajay Bhosale <ajay.bhosale@silicus.com>
 * @license  Silicus http://www.silicus.com/
 * @version  Release:<v.1>
 * @link     None
 */
class ResetPasswordController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */

use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @name   showResetForm
     * @access public
     * @author Ajay Bhosale <ajay.bhosale@silicus.com>
     *
     * @param Request $request page request
     * @param string  $token   session token
     *
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
        if (is_null($token)) {
            return $this->getEmail();
        }
        $email = $request->input('email');
        if (property_exists($this, 'resetView')) {
            return view($this->resetView)->with(compact('token', 'email'));
        }
        if (view()->exists($this->theme . '.auth.passwords.reset')) {
            return view($this->theme . '.auth.passwords.reset')->with(compact('token', 'email'));
        }
        return view($this->theme . '.auth.reset')->with(compact('token', 'email'));
    }

}
