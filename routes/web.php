<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmailMarketingPlatformController;
use App\Http\Controllers\EmailMarketingLinkController;
use App\Http\Controllers\EmbedController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Models\emailMarketingPlatform;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/cl/{link_identifier}', [EmailMarketingLinkController::class, 'handleHit'])->name('marketing_link.handleHit');
Route::get('/embed/{user_unique_public_id}', [EmbedController::class, 'index'])->name('embed.index');

Route::get('/dashboard', function () {
    $providers = emailMarketingPlatform::get();

    return Inertia::render('Dashboard', [
        "email_providers" => $providers,
        "embedded" => false,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/email_platform', [EmailMarketingPlatformController::class, 'index'])->name('email_platform.index');
    Route::get('/email_platform/{email_platform_id}', [EmailMarketingPlatformController::class, 'show'])->name('email_platform.show');
    Route::post('/email_platform', [EmailMarketingPlatformController::class, 'store'])->name('email_platform.store');
    Route::patch('/email_platform/{email_platform_id}', [EmailMarketingPlatformController::class, 'update'])->name('email_platform.update');

    Route::get('/marketing_link', [EmailMarketingLinkController::class, 'index'])->name('marketing_link.index');

});

require __DIR__.'/auth.php';
