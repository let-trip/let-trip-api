<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use Illuminate\Http\Request;

class AttractionController extends Controller
{
    //
    public function index()
    {
        //index all categories api
        $attraction = Attraction::with('categories')->get();
        return response()->json([
            "total" => $attraction->count(),
            "data" =>$attraction,
        ]);
    }
}
