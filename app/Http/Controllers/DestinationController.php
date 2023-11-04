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
            "count" => $destinations->count(),
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
        $destination->coordinate_lat = $request->coordinate_lat;
        $destination->coordinate_long = $request->coordinate_long;
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
            "count" => $destination->count(),
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
        $data['coordinate_lat'] = $request->coordinate_lat;
        $data['coordinate_long'] = $request->coordinate_long;

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
                "count" => $destinations->count(),
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
                "count" => $destinations->count(),
                "status" => 200,
            ]);
        } else {
            return response()->json(['error' => 'Destination not found'], 404);
        }
    }

    public function nearbyDestination(Request $request)
    {
        $input = $request->all(); // Get all input data from the request

        $lat = $input['lat'] ?? null; // Get the 'lat' parameter from the input data
        $long = $input['long'] ?? null; // Get the 'long' parameter from the input data

        if ($lat === null || $long === null) {
            return response()->json(['error' => 'Latitude and longitude are required'], 400);
        }

        $radius = 1; // 10km radius

        // Haversine formula to calculate distances
        $destinations = Destination::select('*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) * cos( radians( coordinate_lat ) ) * cos( radians( coordinate_long ) - radians(?) ) + sin( radians(?) ) * sin( radians( coordinate_lat ) ) ) ) AS distance',
                [$lat, $long, $lat]
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();

        if ($destinations->isNotEmpty()) {
            return response()->json([
                "destination" => $destinations,
                "count" => $destinations->count(),
                "status" => 200,
            ]);
        } else {
            return response()->json(['error' => 'Destination not found within the specified range'], 404);
        }
    }

    //get popular destination with views more than 20 views and order by views desc and limit 5 data only  (popular destination)
    public function popularDestination()
    {
        $destinations = Destination::where('views', '>', 10)->orderBy('views', 'desc')->limit(5)->get();
        return response()->json([
            "destination" => $destinations,
            "count" => $destinations->count(),
            "status" => 200,
        ]);
    }

}
