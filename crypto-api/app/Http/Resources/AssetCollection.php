<?php

namespace App\Http\Resources;

use App\Services\AssetsService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AssetCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $total = AssetsService::calculateTotal( auth()->user()->id );

        return [
            'total' => $total,
            'data' => $this->collection,
        ];
    }
}
