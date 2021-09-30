<?php

namespace App\Http\Requests;

use Config;
use Illuminate\Validation\Rule;

class AssetRequest extends ApiFormRequest
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
            'label' => 'required|max:255',
            'crypto' => ['required', Rule::in(Config::get('crypto.cryptos'))],
            'amount' => 'required|numeric|min:0',
        ];
    }
}
