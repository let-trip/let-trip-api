<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //index method to display all lecturers
        $lecturers = Lecturer::all();
        //api response with pagination and status code 200 and count of lecturers
        return response()->json([
            'status' => 200,
            'count' => $lecturers->count(),
            'data' => $lecturers
        ]);
    }

    //get randon lecturer only one
    public function randomLecturer(){
        $lecturer = Lecturer::inRandomOrder()->first();
        return response()->json([
            'status' => 200,
            'data' => $lecturer
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //store
        //      $table->string('name');
        //            $table->string('email');
        //            $table->string('phone');
        //            $table->string('telegram');
        //            $table->string('description');
        //            $table->string('quote');
        //            $table->string('photo');
        //            $table->string('note');
        //            $table->string('student_relation');
        //            $table->string('skill');
        //            $table->string('photo_album');


        $lecturer = new Lecturer();
        $lecturer->name = $request->name;
        $lecturer->email = $request->email;
        $lecturer->phone = $request->phone;
        $lecturer->telegram = $request->telegram;
        $lecturer->description = $request->description;
        $lecturer->quote = $request->quote;
        $lecturer->photo = $request->photo;

        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->getRealPath();
            $uploadedImage = Cloudinary::upload($imagePath)->getSecurePath();
            $lecturer->photo = $uploadedImage;
        } else if ($request->photo) {
            $imageBase64 = $request->photo;
            $uploadedImage = Cloudinary::upload("data:image/png;base64," . $imageBase64)->getSecurePath();
            $lecturer->photo = $uploadedImage;
        }

        $lecturer->note = $request->note;
        $lecturer->student_relation = $request->student_relation;
        $lecturer->skill = $request->skill;
        $lecturer->photo_album = $request->photo_album;
        $lecturer->save();
        return response()->json([
            'status' => 200,
            'message' => 'Lecturer created successfully',
            'data' => $lecturer
        ]);


    }

    /**
     * Display the specified resource.
     */
    public function show(Lecturer $lecturer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lecturer $lecturer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecturer $lecturer)
    {
        //
    }
}
