<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMatch extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->user_id ?true:false;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'total_over'=>'required | integer | min:1',
            'location'=>'required | string | filled',
            'match_time'=>'required | filled ',
            'total_player'=>'required | integer | min:1',
            'team1'=>'required',
            'team2'=>'required'
        ];
    }
}
