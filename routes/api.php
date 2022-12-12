<?php

use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\BookAuthorController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\UserController;
use App\Models\BookAuthor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/sample', function(){
    return response()->json([ 'message'=> 'welcome' ]);
});

Route::group(['middleware' => ['json.response']], function () {

    Route::get('/bookscounter', [BooksController::class, 'typeCounter']);
    Route::get('/authorcounter', [BooksController::class, 'authorCounter']);
    Route::get('/create_random_user', [BooksController::class, 'createRandomUser']);
    Route::get('/read_random_user/{filename}', [BooksController::class, 'readRandomUser']);
    Route::get('/get_country', [BooksController::class, 'getCountry']);
    Route::get('/get_specific_country/{code}', [BooksController::class, 'getSpecificCountry']);
   
    Route::post('/user/login', [UserController::class, 'login']);
    Route::middleware(['auth:api'])->group(function(){
        Route::get('/user/profile', [UserController::class, 'profile']);
        Route::post('/user/logout', [UserController::class, 'logout']);
        Route::apiResource('/user', UserController::class);
        Route::apiResource('/authors', AuthorsController::class);
        Route::apiResource('/books', BooksController::class);
    });
    Route::resource('/user', UserController::class);


});