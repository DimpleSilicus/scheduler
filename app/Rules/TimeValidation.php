<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TimeValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //When user enter multiple time value as character is not working. 
        for($i=0;$i<count($value);$i++)
        {
            if(!ctype_alpha($value[$i]))
            {
                $parts = explode(':', $value[$i]);            
                if(count($parts) > 3){
                    return false;
                }
                $hours = $parts[0];
                $minutes = $parts[1];
                $seconds=$parts[2];
                if($hours >= 0 && $hours < 24 && $minutes >= 0 && $minutes < 60 && is_numeric($hours) && is_numeric($minutes) && $seconds>=0 && $seconds<60 && is_numeric($seconds)){                
//                    echo "if loop";
                    return true;
                }
                else
                {
//                    echo "else loop1";
                    return false;
                }
            }
            else
            {
//                echo "else loop2";
                return false;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please enter valid time.';
    }
}
