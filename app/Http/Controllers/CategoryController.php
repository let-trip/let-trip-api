<?php

namespace App\Http\Controllers;

use App\Models\Category;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //index all categories api

        $categories = Category::with('destinations')->get();
        return response()->json([
                "categories" =>$categories,
                "status" => 200,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //store all request in database
        $category = new Category();
        $category->category_title = $request->category_title;
        // upload image to storage for Cloudinary
        if ($request->hasFile('category_image')) {
            $imagePath = $request->file('category_image')->getRealPath();
            $uploadedImage = Cloudinary::upload($imagePath)->getSecurePath();
            $category->category_image = $uploadedImage;
        } else if ($request->category_image) {
            $imageBase64 = $request->category_image;
            $uploadedImage = Cloudinary::upload("data:image/png;base64," . $imageBase64)->getSecurePath();
            $category->category_image = $uploadedImage;
        }

        $category->save();
        return response()->json([
            "message" => "Category added successfully!",
            "status" => 200,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //show categories api with id
        $category = Category::with('destinations')->find($id);
        return response()->json([
            "category" => $category,
            "status" => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the category with the given ID or throw an exception if not found
        $category = Category::findOrFail($id);

        // Get all the input data from the request
        $data = $request->all();

        // Check if 'category_image' is present in the request
        if ($request->hasFile('category_image') || $request->filled('category_image')) {
            // Retrieve the old image from Cloudinary
            $oldImage = Cloudinary::getImage($category->category_image);

            // If the old image exists, delete it from Cloudinary
            if ($oldImage->getPublicId() != null) {
                Cloudinary::destroy($oldImage->getPublicId());
            }

            // Set the new image based on the type of input (file or base64)
            if ($request->hasFile('category_image')) {
                $imagePath = $request->file('category_image')->getRealPath();
                $uploadedImage = Cloudinary::upload($imagePath)->getSecurePath();
            } else {
                $imageBase64 = $request->category_image;
                $uploadedImage = Cloudinary::upload("data:image/png;base64," . $imageBase64)->getSecurePath();
            }

            // Update the 'category_image' field in the data array
            $data['category_image'] = $uploadedImage;
        }

        // Update the category with the updated data
        $category->update($data);

        // Return a JSON response indicating the success of the update
        return response()->json([
            "message" => "Category updated successfully!",
            "category" => $category,
            "status" => 200,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //destroy categories api with id
        $category = Category::find($id);
        $category->delete();
        return response()->json([
            "message" => "Category deleted successfully!",
            "status" => 200,
        ]);
    }

    //get category by name request
    public function getCategoryByName(Request $request)
    {
        $category = Category::where('category_title', 'LIKE', '%' . $request->category_title . '%')->get();
        //if category not found
        if ($category->isEmpty()) {
            return response()->json([
                "message" => "Category not found!",
                "status" => 404,
            ]);
        }else{
            return response()->json([
                "category" => $category,
                "status" => 200,
            ]);
        }
    }
}
