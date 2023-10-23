<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //index all destinations from database
        $destinations = Destination::all();
        return response()->json([
            "destinations" =>$destinations,
            "status" => 200,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //store all request in database
        $destination = new Destination();
        $destination->title = $request->title;
        $destination->description = $request->description;
        $destination->address = $request->address;
        $destination->views = $request->views;
        $destination->area = $request->area;

        $destination->images = $request->images;
        // upload image to storage for Cloudinary
        if ($request->hasFile('images')) {
            $imagePath = $request->file('images')->getRealPath();
            $uploadedImage = Cloudinary::upload($imagePath)->getSecurePath();
            $destination->images = $uploadedImage;
        } else if ($request->images) {
            $imageBase64 = $request->images;
            $uploadedImage = Cloudinary::upload("data:image/png;base64," . $imageBase64)->getSecurePath();
            $destination->images = $uploadedImage;
        }
        $destination->save();
        return response()->json([
            "message" => "Destination added successfully!",
            "status" => 200,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //show with id api
        $destination = Destination::find($id);
        return response()->json([
            "destination" => $destination,
            "status" => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //update with id api
        $destination = Destination::find($id);

        // Get all the input data from the request
        $data = $request->all();

        // Check if '$carousel' is present in the request
        if ($request->hasFile('images') || $request->filled('images')) {
            // Retrieve the old image from Cloudinary
            $oldImage = Cloudinary::getImage($destination->images);

            // If the old image exists, delete it from Cloudinary
            if ($oldImage->getPublicId() != null) {
                Cloudinary::destroy($oldImage->getPublicId());
            }

            // Set the new image based on the type of input (file or base64)
            if ($request->hasFile('images')) {
                $imagePath = $request->file('images')->getRealPath();
                $uploadedImage = Cloudinary::upload($imagePath)->getSecurePath();
            } else {
                $imageBase64 = $request->images;
                $uploadedImage = Cloudinary::upload("data:image/png;base64," . $imageBase64)->getSecurePath();
            }
            $data['images'] = $uploadedImage;
        }

        $data['title'] = $request->title;
        $data['description'] = $request->description;
        $data['address'] = $request->address;
        $data['views'] = $request->views;
        $data['area'] = $request->area;

        // Update the $carousel with the updated data
        $destination->update($data);
        return response()->json([
            "message" => "Destination updated successfully!",
            "destination" => $destination,
            "status" => 200,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //destroy with id api
        $destination = Destination::find($id);
        $destination->delete();
        return response()->json([
            "message" => "Destination deleted successfully!",
            "status" => 200,
        ]);
    }

    //research by name
    public function search(Request $request)
    {

        $search = $request->get('search');
        $destinations = Destination::where('title', '%', $search)->first();
        if ($destinations) {
            return response()->json([
                "destination" => $destinations,
                "status" => 200,
            ]);
        } else {
            return response()->json(['error' => 'Destination not found'], 404);
        }
    }

    public function searchByChar(Request $request)
    {
        $search = $request->get('search');
        //search by char similar
        $destinations = Destination::where('title', 'like', '%' . $search . '%')->get();
        if ($destinations) {
            return response()->json([
                "destination" => $destinations,
                "status" => 200,
            ]);
        } else {
            return response()->json(['error' => 'Destination not found'], 404);
        }
    }
}
