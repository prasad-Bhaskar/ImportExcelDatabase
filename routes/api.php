<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/upload', [CategoryController::class, 'upload']);
Route::get('/upload', function () {
    $category = Category::all();
    return response()->json(['category' => $category]);
});

Route::post('/upload-product', [ProductController::class, 'uploadProduct']);

Route::post('/upload-mandal-member', [MemberController::class, 'upoloadMandalUser']);
Route::post('/upload-district-member', [MemberController::class, 'uploadDistrictUser']);
Route::post('/upload-state-member', [MemberController::class, 'uploadStateUser']);


