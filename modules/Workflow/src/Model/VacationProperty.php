<?php

namespace Modules\Workflow\Model;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Support\Facades\Auth;

class VacationProperty extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vacation_properties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['description', 'image_url'];

    
    public static function addProperty($user_id,$description,$file)
    {
//        echo "addProperty".$user_id.":::".$description;
        $fileExtension = $file->getClientOriginalExtension();
        $newFileName = time() . uniqid() . "." . $fileExtension;
        
//Total path is C:\wamp64\www\scheduler\public\storage\app\public\images\1\imageName
        $destinationPath = 'storage/app/public/images/' . $user_id;

        // move Uploaded File
        $file->move($destinationPath, $newFileName);
        
        // uploaded file path
        $filePath = $destinationPath . '/' . $newFileName;
        
        // add data to database
        $objPictures = new self();
        
        $objPictures->user_id = $user_id;
        $objPictures->description = $description;
        $objPictures->image_url = $filePath;
        
        $success = $objPictures->save();
        
        return $success;
    }




    /**
     * Get the user that owns the property.
     */
//    public function user()
//    {
//        return $this->belongsTo('App\User');
//    }
//
//    public function reservations()
//    {
//        return $this->hasMany('App\Reservation');
//    }
}
