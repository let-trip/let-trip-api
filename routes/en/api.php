<?php


use App\Http\Controllers\AttractionController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



//Route::apiResource("Attractions", CategoryController::class);
//Route::post("Attractions/{id}", [CategoryController::class, "update"])->name("category-update");
////set route to get category by name use method post
//Route::post("categoryName", [CategoryController::class, "getCategoryByName"])->name("category-name");

//get Attractions All
Route::get("Attractions/All", [AttractionController::class, "index"])->name("AttractionsAll");
