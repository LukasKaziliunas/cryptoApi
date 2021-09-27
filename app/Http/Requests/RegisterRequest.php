<?php

namespace App\Http\Requests;

class RegisterRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|alpha|max:255',
            'lastname'=>'required|alpha|max:255',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required|min:8|max:255',
            'password2'=>'required|same:password|max:255'
        ];
    }
}
