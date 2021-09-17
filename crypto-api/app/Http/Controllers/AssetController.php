<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        //apskaiciuoti total

        return Asset::where('user_id', auth()->id())->get();
    }

    public function show(Asset $asset)
    {
        return $asset;
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

    public function update(AssetRequest $request, Asset $asset)
    {
        $asset = Asset::create( $request->validated() );

        return response()->json([
            'message' => 'asset updated.',
            'id' => $asset->id
        ], 200);
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return response()->json([
            'message' => 'asset deleted.',
        ], 200);
    }
}
