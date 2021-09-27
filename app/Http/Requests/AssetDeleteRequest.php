<?php

namespace App\Http\Requests;

class AssetDeleteRequest extends ApiFormRequest
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
        return [];
    }
}
