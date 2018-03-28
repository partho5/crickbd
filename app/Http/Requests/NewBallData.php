<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class NewBallData extends FormRequest
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
            'player_bat'=>'required | integer ',
            'player_bowl'=>'required |integer ',
            'ball_number'=>[
                'string',
                'required',
                'regex:/\d+[.][0-5]/i'
            ],
            'incident'=>'nullable | string ',
            'run'=>'required | integer',
            'extra_type'=>'nullable | string',
            'non_strike'=>'required | integer',
            'who_out'=>'integer'
        ];
    }
}
