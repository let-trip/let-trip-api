<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class CarouselController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //index all carousel images api
        $carousel = Carousel::all();
        return response()->json([
            "carousels" =>$carousel,
            "status" => 200,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //store all request in database
        $carousel = new Carousel();
        $carousel->carousel_image = $request->carousel_image;
        // upload image to storage for Cloudinary
        if ($request->hasFile('carousel_image')) {
            $imagePath = $request->file('carousel_image')->getRealPath();
            $uploadedImage = Cloudinary::upload($imagePath)->getSecurePath();
            $carousel->carousel_image = $uploadedImage;
        } else if ($request->carousel_image) {
            $imageBase64 = $request->carousel_image;
            $uploadedImage = Cloudinary::upload("data:image/png;base64," . $imageBase64)->getSecurePath();
            $carousel->carousel_image = $uploadedImage;
        }
        $carousel->save();
        return response()->json([
            "message" => "Carousel added successfully!",
            "status" => 200,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //show with id api
        $carousel = Carousel::find($id);
        return response()->json([
            "carousel" => $carousel,
            "status" => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the $carousel with the given ID or throw an exception if not found
        $carousel = Carousel::findOrFail($id);

        // Get all the input data from the request
        $data = $request->all();

        // Check if '$carousel' is present in the request
        if ($request->hasFile('carousel_image') || $request->filled('carousel_image')) {
            // Retrieve the old image from Cloudinary
            $oldImage = Cloudinary::getImage($carousel->carousel_image);

            // If the old image exists, delete it from Cloudinary
            if ($oldImage->getPublicId() != null) {
                Cloudinary::destroy($oldImage->getPublicId());
            }

            // Set the new image based on the type of input (file or base64)
            if ($request->hasFile('carousel_image')) {
                $imagePath = $request->file('carousel_image')->getRealPath();
                $uploadedImage = Cloudinary::upload($imagePath)->getSecurePath();
            } else {
                $imageBase64 = $request->carousel_image;
                $uploadedImage = Cloudinary::upload("data:image/png;base64," . $imageBase64)->getSecurePath();
            }
            $data['carousel_image'] = $uploadedImage;
        }

        // Update the $carousel with the updated data
        $carousel->update($data);

        // Return a JSON response indicating the success of the update
        return response()->json([
            "message" => "Carousel updated successfully!",
            "carousel" => $carousel,
            "status" => 200,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //destroy with id api
        $carousel = Carousel::find($id);
        $carousel->delete();
        return response()->json([
            "message" => "Carousel deleted successfully!",
            "status" => 200,
        ]);
    }
}
