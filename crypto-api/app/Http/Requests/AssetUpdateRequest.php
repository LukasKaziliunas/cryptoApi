<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetUpdateRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $asset = request()->route('asset');
        $userId = auth()->user()->id;
        if($asset->user_id == $userId)
            return true;
        
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
            'crypto' => 'required',
            'amount' => 'required|numeric|min:0',
        ];
    }
}
