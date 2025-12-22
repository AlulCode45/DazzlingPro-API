<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API V1
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */
    Route::post('/auth/login', [App\Http\Controllers\Api\V1\AuthController::class, 'login']);
    Route::post('/auth/register', [App\Http\Controllers\Api\V1\AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [App\Http\Controllers\Api\V1\AuthController::class, 'logout']);
        Route::get('/auth/profile', [App\Http\Controllers\Api\V1\AuthController::class, 'profile']);
        Route::put('/auth/profile', [App\Http\Controllers\Api\V1\AuthController::class, 'updateProfile']);
        Route::post('/auth/change-password', [App\Http\Controllers\Api\V1\AuthController::class, 'changePassword']);
        Route::post('/refresh-token', [App\Http\Controllers\Api\V1\AuthController::class, 'refreshToken']);
    });

    /*
    |--------------------------------------------------------------------------
    | PUBLIC API (LANDING PAGE - NO AUTH)
    |--------------------------------------------------------------------------
    */
    Route::prefix('public')->group(function () {

        // Events
        Route::get('/events', [App\Http\Controllers\Api\V1\EventController::class, 'index']);
        Route::get('/events/{event}', [App\Http\Controllers\Api\V1\EventController::class, 'show']);

        // Testimonials
        Route::get('/testimonials', [App\Http\Controllers\Api\V1\TestimonialController::class, 'index']);

        // Galleries
        Route::get('/galleries', [App\Http\Controllers\Api\V1\GalleryController::class, 'index']);
        Route::get('/gallery-categories', [App\Http\Controllers\Api\V1\GalleryController::class, 'categories']);

        // Services
        Route::get('/services', [App\Http\Controllers\Api\V1\ServiceController::class, 'index']);

        // Categories
        Route::get('/categories', [App\Http\Controllers\Api\V1\CategoryController::class, 'index']);

        // Portfolios
        Route::get('/portfolios', [App\Http\Controllers\Api\V1\PortfolioController::class, 'index']);
        Route::get('/portfolios/featured', [App\Http\Controllers\Api\V1\PortfolioController::class, 'featured']);
        Route::get('/portfolio-categories', [App\Http\Controllers\Api\V1\PortfolioController::class, 'categories']);

        // Partners
        Route::get('/partners', [App\Http\Controllers\Api\V1\PartnerController::class, 'index']);
        Route::get('/partners/grouped', [App\Http\Controllers\Api\V1\PartnerController::class, 'grouped']);
        Route::get('/partners/by-type/{type}', [App\Http\Controllers\Api\V1\PartnerController::class, 'getByType']);

        // Event Rentals
        Route::get('/event-rentals', [App\Http\Controllers\Api\V1\EventRentalController::class, 'index']);
        Route::get('/event-rentals/featured', [App\Http\Controllers\Api\V1\EventRentalController::class, 'featured']);
        Route::get('/event-rentals/{type}', [App\Http\Controllers\Api\V1\EventRentalController::class, 'getByType']);
        Route::post('/event-rentals/check-availability', [App\Http\Controllers\Api\V1\EventRentalController::class, 'checkAvailability']);

        // Company Info
        Route::get('/company-information', [App\Http\Controllers\Api\V1\CompanyInformationController::class, 'getActive']);

        // Hero Section
        Route::get('/hero-sections', [App\Http\Controllers\Api\V1\HeroSectionController::class, 'getActive']);

        // Teams
        Route::get('/teams', [App\Http\Controllers\Api\V1\TeamController::class, 'getActive']);

        // FAQs
        Route::get('/faqs', [App\Http\Controllers\Api\V1\FAQController::class, 'getActive']);

        // Page Sections
        Route::get('/page-sections', [App\Http\Controllers\Api\V1\PageSectionController::class, 'publicIndex']);
        Route::get('/page-sections/{key}', [App\Http\Controllers\Api\V1\PageSectionController::class, 'getByKey']);
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN / DASHBOARD (AUTH REQUIRED)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {

        Route::apiResource('events', App\Http\Controllers\Api\V1\EventController::class);
        Route::apiResource('testimonials', App\Http\Controllers\Api\V1\TestimonialController::class);
        Route::apiResource('galleries', App\Http\Controllers\Api\V1\GalleryController::class);

        // Gallery categories - use custom route to get categories list
        Route::get('gallery-categories', [App\Http\Controllers\Api\V1\GalleryController::class, 'categories']);

        Route::apiResource('services', App\Http\Controllers\Api\V1\ServiceController::class);
        Route::apiResource('categories', App\Http\Controllers\Api\V1\CategoryController::class);
        Route::apiResource('portfolios', App\Http\Controllers\Api\V1\PortfolioController::class);
        Route::apiResource('portfolio-categories', App\Http\Controllers\Api\V1\PortfolioController::class);
        Route::apiResource('partners', App\Http\Controllers\Api\V1\PartnerController::class);

        Route::apiResource('company-information', App\Http\Controllers\Api\V1\CompanyInformationController::class);
        Route::apiResource('hero-sections', App\Http\Controllers\Api\V1\HeroSectionController::class);
        Route::apiResource('teams', App\Http\Controllers\Api\V1\TeamController::class);
        Route::apiResource('faqs', App\Http\Controllers\Api\V1\FAQController::class);
        Route::apiResource('page-sections', App\Http\Controllers\Api\V1\PageSectionController::class);
        Route::apiResource('event-rentals', App\Http\Controllers\Api\V1\EventRentalController::class);

        // Upload
        Route::prefix('upload')->group(function () {
            Route::post('/company-asset', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadCompanyAsset']);
            Route::post('/gallery-image', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadGalleryImage']);
            Route::post('/rental-image', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadRentalImage']);
            Route::post('/team-photo', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadTeamPhoto']);
            Route::post('/service-image', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadServiceImage']);
            Route::post('/hero-image', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadHeroImage']);
        });

        // Admin only
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('users', App\Http\Controllers\Api\V1\UserController::class);
        });
    });
});
