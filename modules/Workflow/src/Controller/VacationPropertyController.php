<?php
namespace Modules\Workflow\Controller;

use Illuminate\Http\Request;
//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Workflow\Model\VacationProperty;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

class VacationPropertyController extends Controller
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
     * Store a new property
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createNewProperty(Request $request, Authenticatable $user)
    {
        $this->validate(
            $request, [
                'description' => 'required|string',
//                'image_url' => 'required|url'
            ]
        );
        $file = $request->file('image_url');
        $insert = VacationProperty::addProperty(Auth::id(), $request->description, $file);
        
        if ($insert) {
            \Session::flash('success', 'Property uploaded successfully.');
        }
        return redirect('properties');
    }

    public function index()
    {       
        
        $properties = VacationProperty::All();
        return view('Workflow::property.index', ['properties' => $properties]);
    }

    public function show($id)
    {
        $property = VacationProperty::find($id);
//        print_r($property);dd('asd');
        return view('Workflow::property.show', ['property' => $property]);
    }

    public function editForm($id)
    {
        $property = VacationProperty::find($id);

        return view('Workflow::property.edit', ['property' => $property]);
    }

    public function editProperty(Request $request, $id) {
        $property = VacationProperty::find($id);
        $property->update($request->all());

        return redirect()->route('property-show', ['id' => $id]);
    }
}
