<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        return response()->json(Banner::all(), 200);
    }

    public function show($filename = null)
    {
        $path = storage_path() . '/app/banners/' . $filename;

        if (file_exists($path)) {
            return response()->file($path);
        }
        
        return response()->json([], 404);
    }

    public function store()
    {
        // validation

        $banner = request()->file('banner');

        $result = Banner::create(
            [
                'path' => $banner->store('banners'),
                'filename' => $banner->getClientOriginalName(),
                'extension' => $banner->getClientOriginalExtension(),
                'url' => request()->url
            ]
        );

        return response()->json($result, 201);
    }
}
