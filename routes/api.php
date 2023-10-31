<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\UserAuthController;
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
//set route to get category by name use method post
Route::post("categoryName", [CategoryController::class, "getCategoryByName"])->name("category-name");

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

//banners
Route::apiResource("banner", BannerController::class);
Route::post("banner/{id}", [BannerController::class, "update"])->name("banner-update");


//user auth
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
//login with token
Route::post('/auth/login/token', [AuthController::class, 'loginWithToken']);
