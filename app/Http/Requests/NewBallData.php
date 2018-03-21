<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
                'required',
                'regex:/\d+[.][1-6]/i'
            ],
            'incident'=>'required | string ',
            'run'=>'required | integer'
        ];
    }
}
