<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UploadController extends Controller
{
    /**
     * Upload company assets (logo, favicon)
     */
    public function uploadCompanyAsset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:2048', // Max 2MB
            'type' => 'required|in:logo,favicon'
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendError('Validation error', $validator->errors(), 422);
        }

        try {
            $file = $request->file('file');
            $type = $request->input('type');

            // File validation based on type
            $allowedMimes = $type === 'favicon'
                ? ['image/jpeg', 'image/png', 'image/x-icon', 'image/vnd.microsoft.icon']
                : ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return ApiResponse::sendError('Invalid file type', [], 400);
            }

            // Create directory if not exists
            $directory = "company/{$type}";

            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Process and save image
            $image = Image::make($file);

            // Resize based on type
            if ($type === 'logo') {
                // Logo: max width 400px, max height 200px
                $image->resize(400, 200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } elseif ($type === 'favicon') {
                // Favicon: 32x32px
                $image->resize(32, 32);
            }

            // Store the processed image
            $path = $directory . '/' . $filename;

            // Save to storage
            $image->stream();
            Storage::disk('public')->put($path, $image->getEncoded());

            // Get public URL
            $url = asset('storage/' . $path);

            return ApiResponse::sendResponse('File uploaded successfully', [
                'url' => $url,
                'path' => $path,
                'type' => $type
            ]);

        } catch (\Exception $e) {
            return ApiResponse::sendError('Failed to upload file', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Upload gallery images
     */
    public function uploadGalleryImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:5120', // Max 5MB
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendError('Validation error', $validator->errors(), 422);
        }

        try {
            $file = $request->file('file');

            // Validate image
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return ApiResponse::sendError('Invalid file type', [], 400);
            }

            // Create directory
            $directory = "gallery/" . date('Y/m');

            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Process and save image
            $image = Image::make($file);

            // Gallery: max width 1200px, quality 85%
            if ($image->width() > 1200) {
                $image->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // Create thumbnail
            $thumbnail = Image::make($file);
            $thumbnail->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            // Save main image
            $path = $directory . '/' . $filename;
            $image->stream();
            Storage::disk('public')->put($path, $image->getEncoded());

            // Save thumbnail
            $thumbPath = $directory . '/thumb_' . $filename;
            $thumbnail->stream();
            Storage::disk('public')->put($thumbPath, $thumbnail->getEncoded());

            // Get URLs
            $url = asset('storage/' . $path);
            $thumbnailUrl = asset('storage/' . $thumbPath);

            return ApiResponse::sendResponse('Image uploaded successfully', [
                'url' => $url,
                'thumbnail_url' => $thumbnailUrl,
                'path' => $path
            ]);

        } catch (\Exception $e) {
            return ApiResponse::sendError('Failed to upload image', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Upload rental images
     */
    public function uploadRentalImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:5120', // Max 5MB
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendError('Validation error', $validator->errors(), 422);
        }

        try {
            $file = $request->file('file');

            // Validate image
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return ApiResponse::sendError('Invalid file type', [], 400);
            }

            // Create directory
            $directory = "rental/" . date('Y/m');

            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Process and save image
            $image = Image::make($file);

            // Rental: max width 800px, quality 85%
            if ($image->width() > 800) {
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // Save image
            $path = $directory . '/' . $filename;
            $image->stream();
            Storage::disk('public')->put($path, $image->getEncoded());

            // Get URL
            $url = asset('storage/' . $path);

            return ApiResponse::sendResponse('Image uploaded successfully', [
                'url' => $url,
                'path' => $path
            ]);

        } catch (\Exception $e) {
            return ApiResponse::sendError('Failed to upload image', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Upload team member photo
     */
    public function uploadTeamPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendError('Validation error', $validator->errors(), 422);
        }

        try {
            $file = $request->file('file');

            // Validate image
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return ApiResponse::sendError('Invalid file type', [], 400);
            }

            // Create directory
            $directory = "team/" . date('Y/m');

            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Process and save image
            $image = Image::make($file);

            // Team photo: 300x300px square
            $image->fit(300, 300, function ($constraint) {
                $constraint->upsize();
            });

            // Save image
            $path = $directory . '/' . $filename;
            $image->stream();
            Storage::disk('public')->put($path, $image->getEncoded());

            // Get URL
            $url = asset('storage/' . $path);

            return ApiResponse::sendResponse('Photo uploaded successfully', [
                'url' => $url,
                'path' => $path
            ]);

        } catch (\Exception $e) {
            return ApiResponse::sendError('Failed to upload photo', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Upload service cover image
     */
    public function uploadServiceImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:5120', // Max 5MB
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendError('Validation error', $validator->errors(), 422);
        }

        try {
            $file = $request->file('file');

            // Validate image
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return ApiResponse::sendError('Invalid file type', [], 400);
            }

            // Create directory
            $directory = "services/" . date('Y/m');

            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Process and save image
            $image = Image::make($file);

            // Service image: max width 800px, quality 85%
            if ($image->width() > 800) {
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // Save image
            $path = $directory . '/' . $filename;
            $image->stream();
            Storage::disk('public')->put($path, $image->getEncoded());

            // Get URL
            $url = asset('storage/' . $path);

            return ApiResponse::sendResponse('Image uploaded successfully', [
                'url' => $url,
                'path' => $path
            ]);

        } catch (\Exception $e) {
            return ApiResponse::sendError('Failed to upload image', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Upload hero section image
     */
    public function uploadHeroImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:5120', // Max 5MB
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendError('Validation error', $validator->errors(), 422);
        }

        try {
            $file = $request->file('file');

            // Validate image
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return ApiResponse::sendError('Invalid file type', [], 400);
            }

            // Create directory
            $directory = "hero/" . date('Y/m');

            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Process and save image
            $image = Image::make($file);

            // Hero image: max width 1920px, quality 85%
            if ($image->width() > 1920) {
                $image->resize(1920, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // Save image
            $path = $directory . '/' . $filename;
            $image->stream();
            Storage::disk('public')->put($path, $image->getEncoded());

            // Get URL
            $url = asset('storage/' . $path);

            return ApiResponse::sendResponse('Image uploaded successfully', [
                'url' => $url,
                'path' => $path
            ]);

        } catch (\Exception $e) {
            return ApiResponse::sendError('Failed to upload image', ['error' => $e->getMessage()], 500);
        }
    }
}
