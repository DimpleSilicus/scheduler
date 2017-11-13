<?php

/**
 *  Laravel auth controller class file
 *
 * @name       ForgotPasswordController.php
 * @category   Controllers
 * @package    Auth
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    GIT: $Id: c8e7553654d1c055d0c5b734ea2d4c858ad92fe5 $
 * @link       None
 * @filesource
 */

namespace Modules\User\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * Registration & Login Controller
 *
 * This controller handles the registration of new users, as well as the
 * authentication of existing users. By default, this controller uses
 * a simple trait to add these behaviors. Why don't you explore it?
 *
 * @name     ForgotPasswordController
 * @category Controller
 * @package  Auth
 * @author   Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license  Silicus http://www.silicus.com/
 * @version  Release:<v.1>
 * @link     None
 */
class ForgotPasswordController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
     */

use SendsPasswordResetEmails;

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
     * Display the form to request a password reset link.
     *
     * @name   __construct
     * @access public
     * @author Dimple Agarwal <dimple.agarwal@silicus.com>
     *
     * @return Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {

        if (property_exists($this, 'linkRequestView')) {
            return view($this->linkRequestView);
        }

        if (view()->exists($this->theme . '.auth.passwords.email')) {
            return view($this->theme . '.auth.passwords.email');
        }

        return view($this->theme . '.auth.password');
    }

}
