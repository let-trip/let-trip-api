<?php

use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DestinationController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource("category", CategoryController::class);
Route::post("category/{id}", [CategoryController::class, "update"])->name("category-update");

Route::apiResource("carousel", CarouselController::class);
Route::post("carousel/{id}", [CarouselController::class, "update"])->name("carousel-update");

Route::apiResource("destination", DestinationController::class);
Route::post("destination/{id}", [DestinationController::class, "update"])->name("destination-update");

//search
Route::get("searchs", [DestinationController::class, "search"])->name("destination-search");
Route::get("search", [DestinationController::class, "searchByChar"])->name("destination-searchByChar");

//nearby locations
Route::post("nearby", [DestinationController::class, "nearbyDestination"])->name("destination-nearby");

//popular locations
Route::get("popular", [DestinationController::class, "popularDestination"])->name("destination-popular");

