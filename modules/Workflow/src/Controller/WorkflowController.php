<?php
/**
 *  FederalController for getting naics data through api.
 *
 * @name       FederalController
 * @category   Plugin
 * @package    Federal
 * @author     Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    none
 * @link       None
 * @filesource
 */
namespace Modules\Workflow\Controller;

use Illuminate\Http\Request;
//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Workflow\Model\Reservation;
use Modules\Workflow\Model\VacationProperty;
use DB;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

/**
 * FederalController class for Naics operations. 
 *
 * @category FederalController
 * @package Federal
 * @author Dimple Agarwal <dimple.agarwal@silicus.com>
 * @license Silicus http://google.com
 * @name FederalController
 * @version Release:<v.1>
 * @link http://google.com
 */
class WorkflowController extends Controller
{
    /**
    * Create a new controller instance.
    * Use to load JS and CSS files.
    * @return void
    */
    public function __construct()
    {    
        
        parent::__construct();
        $jsFiles[]="";
        $cssFiles[] = $this->url . '/theme/' . Config::get('app.theme') . '/assets/js/main.css';
        $cssFiles[] = $this->url . '/theme/' . Config::get('app.theme') . '/assets/js/scaffolds.css';
        $cssFiles[] = $this->url . '/theme/' . Config::get('app.theme') . '/assets/js/vacation_properties.css';
        $cssFiles[] = $this->url . '/theme/' . Config::get('app.theme') . '/assets/js/application.css';
        $this->loadJsCSS($jsFiles, $cssFiles);
    }
    
    /**
     * Store a new reservation
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Client $client, Request $request, Authenticatable $user, $id)
    {
//        dd('create1'.$user->fullNumber());
        $this->validate(
            $request, [
                'message' => 'required|string'
            ]
        );
        
        print_r($request->all());dd('sd');
        $respond_phone_number=$user->fullNumber();
        $message=$request->message;
        $user_id=Auth::id();
        
        
        
        $property = VacationProperty::find($id);
        $reservation = new Reservation($request->all());
        $reservation->respond_phone_number = $user->fullNumber();
        $reservation->user()->associate($property->user);

        $property->reservations()->save($reservation);

        $this->notifyHost($client, $reservation);

        $request->session()->flash(
            'status',
            "Sending your reservation request now."
        );
        return redirect()->route('property-show', ['id' => $property->id]);
    }
    
    public function acceptReject(Request $request)
    {
        $hostNumber = $request->input('From');
        $smsInput = strtolower($request->input('Body'));
        $host = User::where(DB::raw("CONCAT('+',country_code::text, phone_number::text)"), 'LIKE', "%".$hostNumber."%")
                    ->get()
                    ->first();
        $reservation = $host->pendingReservations()->first();
        $smsResponse = null;
        if (!is_null($reservation))
        {
            if (strpos($smsInput, 'yes') !== false || strpos($smsInput, 'accept') !== false)
            {
                $reservation->confirm();
            }
            else
            {
                $reservation->reject();
            }

            $smsResponse = 'You have successfully ' . $reservation->status . ' the reservation.';
        }
        else
        {
            $smsResponse = 'Sorry, it looks like you don\'t have any reservations to respond to.';
        }

        return response($this->respond($smsResponse, $reservation))->header('Content-Type', 'application/xml');
    }
    
    private function notifyHost($client, $reservation)
    {
        dd('notifyHost');
        $host = $reservation->property->user;

        $twilioNumber = config('services.twilio')['number'];
        $messageBody = $reservation->message . ' - Reply \'yes\' or \'accept\' to confirm the reservation, or anything else to reject it.';

        try {
            $client->messages->create(
                $host->fullNumber(), // Text any number
                [
                    'from' => $twilioNumber, // From a Twilio number in your account
                    'body' => $messageBody
                ]
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
