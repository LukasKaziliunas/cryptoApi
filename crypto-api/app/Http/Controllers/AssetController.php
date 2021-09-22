<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Http\Requests\AssetDeleteRequest;
use App\Http\Requests\AssetUpdateRequest;
use App\Http\Resources\AssetCollection;
use App\Http\Resources\AssetResource;
use App\Interfaces\CryptoApi;
use App\Models\Asset;

class AssetController extends Controller
{
    public function index()
    {
        return new AssetCollection( Asset::where('user_id', auth()->id())->get() );
    }

    public function show(Asset $asset)
    {
        return new AssetResource( $asset );
    }

    public function store(AssetRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $asset = Asset::create( $validated );

        return response()->json([
            'message' => 'asset created.',
            'id' => $asset->id
        ], 201);
    }

    public function update(AssetUpdateRequest $request, Asset $asset)
    {
        $asset->update( $request->validated() );

        return response()->json([
            'message' => 'asset updated.',
            'id' => $asset->id
        ], 200);
    }

    public function destroy(AssetDeleteRequest $request, Asset $asset)
    {
        $asset->delete();

        return response()->json([
            'message' => 'asset deleted.',
        ], 200);
    }

    public function availableCryptos()
    {
        return CryptoApi::CRYPTOS;
    }

    public function test(CryptoApi $cryptoApi)
    {
        // $cmcApi = new CoinMarketCapApi();
        // dd( $cmcApi->getRates()  );
    }
}
