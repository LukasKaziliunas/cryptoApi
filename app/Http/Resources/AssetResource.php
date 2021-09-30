<?php

namespace App\Http\Resources;

use App\Services\AssetsService;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $assetsService = resolve(AssetsService::class);

        $price = $assetsService->calculateAssetPrice($this->crypto, $this->amount);

        return [
            'id' => $this->id,
            'label' => $this->label,
            'amount' => $this->amount,
            'crypto' => $this->crypto,
            'price' => $price,
        ];
    }
}
