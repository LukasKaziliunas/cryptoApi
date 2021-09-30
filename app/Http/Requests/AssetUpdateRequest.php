<?php

namespace App\Http\Requests;

use Config;
use Illuminate\Validation\Rule;

class AssetUpdateRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $asset = $this->route('asset');
        $userId = $this->user()->id;
        if ($asset->user_id == $userId) {
            return true;
        }

        return false;
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
