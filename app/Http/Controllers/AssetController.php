<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetDeleteRequest;
use App\Http\Requests\AssetRequest;
use App\Http\Requests\AssetUpdateRequest;
use App\Http\Resources\AssetCollection;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use Config;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * give a listing of the assets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return new AssetCollection(Asset::where('user_id', $request->user()->id)->get());
    }

    /**
     * give the specified asset.
     *
     * @param  Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {
        return new AssetResource($asset);
    }

    /**
     * Store a newly created asset in storage.
     *
     * @param  AssetRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        $asset = Asset::create($validated);

        return response()->json([
            'message' => 'asset was created.',
            'id' => $asset->id,
        ], 201);
    }

    /**
     * Update the asset.
     *
     * @param  AssetUpdateRequest $request, Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function update(AssetUpdateRequest $request, Asset $asset)
    {
        $asset->update($request->validated());

        return response()->json([
            'message' => 'asset was updated.',
            'id' => $asset->id,
        ], 200);
    }

    /**
     * delete the asset.
     *
     * @param  AssetDeleteRequest $request, Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssetDeleteRequest $request, Asset $asset)
    {
        $asset->delete();

        return response()->json([
            'message' => 'asset was deleted.',
        ], 200);
    }

    /**
     * give a listing of available cryptos.
     *
     * @return \Illuminate\Http\Response
     */
    public function availableCryptos()
    {
        return Config::get('crypto.cryptos');
    }
}
