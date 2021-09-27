<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Interfaces\CryptoApi;
use App\Services\AssetsService;

class AssetResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $price = AssetsService::calculateAssetPrice($this->crypto, $this->amount);

        return [
            'id' => $this->id,
            'label' => $this->label,
            'amount' => $this->amount,
            'crypto' => $this->crypto,
            'price' => $price,
        ];
    }
}
