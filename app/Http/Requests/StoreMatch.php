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
            'total_over'=>'required | integer | max:500 | min:1',
            'location'=>'required | string | filled',
            'match_time'=>'required | filled ',
            'total_player'=>'required | integer | max:100 | min:1'
        ];
    }
}
