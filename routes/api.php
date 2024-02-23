<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailMarketingLinkController;
use App\Http\Controllers\EmbedController;


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

Route::middleware('auth')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});

Route::post('/marketing_link', [EmailMarketingLinkController::class, 'store'])->name('marketing_link.store');  
Route::post('/marketing_link/test_zapier_hook/{marketing_link_id}', [EmailMarketingLinkController::class, 'test_zapier_hook'])->name('marketing_link.test_zapier_hook');  
Route::post('/embed/marketing_link', [EmbedController::class, 'store'])->name('embedMarketing_link.store');  

Route::patch('/marketing_link/{marketing_link_id}', [EmailMarketingLinkController::class, 'update'])->name('marketing_link.update');
