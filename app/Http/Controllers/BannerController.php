<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //index all banners from database
        $banners = Banner::all();
        return response()->json([
            "banners" =>$banners,
            "count" => $banners->count(),
            "status" => 200,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //store all request in database
        $banner = new Banner();
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->discount = $request->discount;
        $banner->views = $request->views;
        $banner->images = $request->images;
        // upload image to storage for Cloudinary
        if ($request->hasFile('images')) {
            $imagePath = $request->file('images')->getRealPath();
            $uploadedImage = Cloudinary::upload($imagePath)->getSecurePath();
            $banner->images = $uploadedImage;
        } else if ($request->images) {
            $imageBase64 = $request->images;
            $uploadedImage = Cloudinary::upload("data:image/png;base64," . $imageBase64)->getSecurePath();
            $banner->images = $uploadedImage;
        }
        $banner->save();
        return response()->json([
            "message" => "Banner added successfully!",
            "status" => 200,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //show banner by id
        $banner = Banner::find($id);
        return response()->json([
            "banner" => $banner,
            "count" => $banner->count(),
            "status" => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //delete banner by id
        $banner = Banner::find($id);
        $banner->delete();
        return response()->json([
            "message" => "Banner deleted successfully!",
            "status" => 200,
        ]);
    }
}
