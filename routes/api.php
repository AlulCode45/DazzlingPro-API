<?php

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

// API Version 1
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/auth/login', [App\Http\Controllers\Api\V1\AuthController::class, 'login']);
    Route::post('/auth/logout', [App\Http\Controllers\Api\V1\AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/auth/register', [App\Http\Controllers\Api\V1\AuthController::class, 'register']);
    Route::get('/auth/profile', [App\Http\Controllers\Api\V1\AuthController::class, 'profile'])->middleware('auth:sanctum');
    Route::put('/auth/profile', [App\Http\Controllers\Api\V1\AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
    Route::post('/auth/change-password', [App\Http\Controllers\Api\V1\AuthController::class, 'changePassword'])->middleware('auth:sanctum');
    Route::post('/refresh-token', [App\Http\Controllers\Api\V1\AuthController::class, 'refreshToken'])->middleware('auth:sanctum');

    // Public routes (no authentication required)
    Route::get('/events', [App\Http\Controllers\Api\V1\EventController::class, 'index']);
    Route::get('/events/{event}', [App\Http\Controllers\Api\V1\EventController::class, 'show']);
    Route::get('/testimonials', [App\Http\Controllers\Api\V1\TestimonialController::class, 'index']);
    Route::get('/galleries', [App\Http\Controllers\Api\V1\GalleryController::class, 'index']);
    Route::get('/gallery-categories', [App\Http\Controllers\Api\V1\GalleryController::class, 'categories']);
    Route::get('/services', [App\Http\Controllers\Api\V1\ServiceController::class, 'index']);
    Route::get('/categories', [App\Http\Controllers\Api\V1\CategoryController::class, 'index']);
    Route::get('/portfolios', [App\Http\Controllers\Api\V1\PortfolioController::class, 'index']);
    Route::get('/portfolio-categories', [App\Http\Controllers\Api\V1\PortfolioController::class, 'categories']);
    Route::get('/portfolios/featured', [App\Http\Controllers\Api\V1\PortfolioController::class, 'featured']);
    Route::get('/partners', [App\Http\Controllers\Api\V1\PartnerController::class, 'index']);
    Route::get('/partners/by-type/{type}', [App\Http\Controllers\Api\V1\PartnerController::class, 'getByType']);
    Route::get('/event-rentals', [App\Http\Controllers\Api\V1\EventRentalController::class, 'index']);
    Route::get('/event-rentals/featured', [App\Http\Controllers\Api\V1\EventRentalController::class, 'featured']);
    Route::get('/event-rentals/{type}', [App\Http\Controllers\Api\V1\EventRentalController::class, 'getByType']);
    Route::post('/event-rentals/check-availability', [App\Http\Controllers\Api\V1\EventRentalController::class, 'checkAvailability']);

    // Company Information (public)
    Route::get('/company-information/active', [App\Http\Controllers\Api\V1\CompanyInformationController::class, 'getActive']);

    // Hero Sections (public)
    Route::get('/hero-sections/active', [App\Http\Controllers\Api\V1\HeroSectionController::class, 'getActive']);

    // Teams (public)
    Route::get('/teams/active', [App\Http\Controllers\Api\V1\TeamController::class, 'getActive']);

    // FAQs (public)
    Route::get('/faqs/active', [App\Http\Controllers\Api\V1\FAQController::class, 'getActive']);
    // Page Sections (public)
    Route::get('/page-sections/public', [App\Http\Controllers\Api\V1\PageSectionController::class, 'publicIndex']);
    Route::get('/page-sections/public/{key}', [App\Http\Controllers\Api\V1\PageSectionController::class, 'getByKey']);

    // Specific routes that might conflict with resource routes
    Route::get('/partners/grouped', [App\Http\Controllers\Api\V1\PartnerController::class, 'grouped'])->name('partners.grouped');

    // Protected routes (require authentication)
    Route::middleware(['auth:sanctum', \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class])->group(function () {
        // Events
        Route::apiResource('events', App\Http\Controllers\Api\V1\EventController::class);

        // Testimonials (exclude index - public route)
        Route::apiResource('testimonials', App\Http\Controllers\Api\V1\TestimonialController::class)->except(['index']);

        // Galleries
        Route::apiResource('galleries', App\Http\Controllers\Api\V1\GalleryController::class);

        // Services (exclude index - public route)
        Route::apiResource('services', App\Http\Controllers\Api\V1\ServiceController::class)->except(['index']);

        // Categories
        Route::apiResource('categories', App\Http\Controllers\Api\V1\CategoryController::class);

        // Portfolios
        Route::apiResource('portfolios', App\Http\Controllers\Api\V1\PortfolioController::class);

        // Partners (exclude index - public route)
        Route::apiResource('partners', App\Http\Controllers\Api\V1\PartnerController::class)->except(['index']);

        // Event Rentals (protected routes only)
        Route::post('/event-rentals', [App\Http\Controllers\Api\V1\EventRentalController::class, 'store']);
        Route::put('/event-rentals/{eventRental}', [App\Http\Controllers\Api\V1\EventRentalController::class, 'update']);
        Route::delete('/event-rentals/{eventRental}', [App\Http\Controllers\Api\V1\EventRentalController::class, 'destroy']);

        // Company Information (protected routes)
        Route::apiResource('company-information', App\Http\Controllers\Api\V1\CompanyInformationController::class);

        // Hero Sections (protected routes)
        Route::apiResource('hero-sections', App\Http\Controllers\Api\V1\HeroSectionController::class);

        // Teams (protected routes)
        Route::apiResource('teams', App\Http\Controllers\Api\V1\TeamController::class);

        // FAQs (protected routes)
        Route::apiResource('faqs', App\Http\Controllers\Api\V1\FAQController::class);
        Route::get('/faqs/categories', [App\Http\Controllers\Api\V1\FAQController::class, 'getCategories']);
        Route::get('/faqs/category/{category}', [App\Http\Controllers\Api\V1\FAQController::class, 'getByCategory']);

        // Page Sections (protected)
        Route::apiResource('page-sections', App\Http\Controllers\Api\V1\PageSectionController::class);

        // Upload routes
        Route::prefix('upload')->group(function () {
            Route::post('/company-asset', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadCompanyAsset']);
            Route::post('/gallery-image', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadGalleryImage']);
            Route::post('/rental-image', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadRentalImage']);
            Route::post('/team-photo', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadTeamPhoto']);
            Route::post('/service-image', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadServiceImage']);
            Route::post('/hero-image', [App\Http\Controllers\Api\V1\UploadController::class, 'uploadHeroImage']);
        });

        // Users (Admin only)
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('users', App\Http\Controllers\Api\V1\UserController::class);
        });
    });
});

