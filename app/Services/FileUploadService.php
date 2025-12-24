<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FileUploadService
{
    /**
     * Image manager instance (Intervention Image v3)
     */
    protected ImageManager $imageManager;

    /**
     * Allowed image extensions
     */
    protected array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * Maximum file size in bytes (5MB)
     */
    protected int $maxFileSize = 5 * 1024 * 1024;

    public function __construct()
    {
        // Use GD driver by default (can switch to Imagick if available)
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Upload an image file
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string|null $oldPath
     * @return array
     * @throws \Exception
     */
    public function uploadImage(UploadedFile $file, string $directory = 'images', ?string $oldPath = null): array
    {
        // Validate file
        $this->validateImage($file);

        // Delete old image if provided
        if ($oldPath) {
            $this->deleteImage($oldPath);
        }

        // Generate unique filename with webp extension
        $filename = $this->generateUniqueFilenameWebp($file);

        // Create directory if it doesn't exist
        $fullDirectory = "public/{$directory}";
        Storage::makeDirectory($fullDirectory);

        // Store the file temporarily with original extension
        $tempFilename = $this->generateUniqueFilename($file);
        $tempPath = $file->storeAs($directory, $tempFilename, 'public');
        $tempFullPath = Storage::disk('public')->path($tempPath);

        // Final WebP path
        $finalPath = $directory . '/' . $filename;
        $finalFullPath = Storage::disk('public')->path($finalPath);

        // Optimize and convert to WebP
        try {
            $image = $this->imageManager->read($tempFullPath);

            // Resize down if width exceeds max (keep aspect ratio)
            if ($image->width() > 1920) {
                $image->scaleDown(width: 1920);
            }

            // Convert to WebP with quality 80
            $bytes = $image->toWebp(quality: 80);
            file_put_contents($finalFullPath, $bytes);

            // Delete temp file after successful conversion
            if (file_exists($tempFullPath)) {
                @unlink($tempFullPath);
            }
        } catch (\Exception $e) {
            // If conversion fails, delete temp and throw exception
            if (file_exists($tempFullPath)) {
                @unlink($tempFullPath);
            }
            throw new \Exception("Failed to process image: " . $e->getMessage());
        }

        // Get public URL with correct disk
        $url = asset('storage/' . $finalPath);

        // Get final file size
        $finalSize = file_exists($finalFullPath) ? filesize($finalFullPath) : 0;

        return [
            'path' => $finalPath,
            'url' => $url,
            'filename' => $filename,
            'size' => $finalSize,
            'mime_type' => 'image/webp'
        ];
    }

    /**
     * Delete an image file
     *
     * @param string $path
     * @return bool
     */
    public function deleteImage(string $path): bool
    {
        // Skip deletion for external URLs (http/https)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return false; // Don't try to delete external URLs
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Validate image file
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    protected function validateImage(UploadedFile $file): void
    {
        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            throw new \Exception("File size must not exceed 5MB");
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new \Exception("Invalid file extension. Allowed: " . implode(', ', $this->allowedExtensions));
        }

        // Check MIME type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception("Invalid file type. Only images are allowed");
        }
    }

    /**
     * Generate unique filename
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $timestamp = now()->format('Y-m-d-His');
        $random = Str::random(10);

        return "{$timestamp}-{$random}.{$extension}";
    }

    /**
     * Generate unique filename with webp extension
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateUniqueFilenameWebp(UploadedFile $file): string
    {
        $timestamp = now()->format('Y-m-d-His');
        $random = Str::random(10);

        return "{$timestamp}-{$random}.webp";
    }

    /**
     * Optimize image for web with aggressive compression
     *
     * @param string $filePath
     * @param array $options
     * @return void
     */
    protected function optimizeImage(string $filePath, array $options = []): void
    {
        try {
            $image = $this->imageManager->read($filePath);

            // Get configuration
            $maxWidth = $options['max_width'] ?? 1920;
            $quality = $options['quality'] ?? 80;
            $convertToWebp = $options['webp'] ?? true;

            // Resize down if width exceeds max (keep aspect ratio)
            if ($image->width() > $maxWidth) {
                $image->scaleDown(width: $maxWidth);
            }

            // Get original extension
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            // Convert to WebP for better compression (if enabled)
            if ($convertToWebp && in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $bytes = $image->toWebp(quality: $quality);
                // Change file extension to webp
                $newPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $filePath);
                file_put_contents($newPath, $bytes);

                // Remove original file if different
                if ($newPath !== $filePath && file_exists($filePath)) {
                    @unlink($filePath);
                }
                return;
            }

            // Otherwise optimize in original format
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $bytes = $image->toJpeg(quality: $quality);
                    break;
                case 'png':
                    // PNG with optimization
                    $bytes = $image->toPng();
                    break;
                case 'webp':
                    $bytes = $image->toWebp(quality: $quality);
                    break;
                default:
                    $bytes = $image->toJpeg(quality: $quality);
            }

            // Overwrite original file with optimized bytes
            file_put_contents($filePath, $bytes);
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            \Log::warning("Image optimization failed: " . $e->getMessage());
        }
    }

    /**
     * Optimize image and convert to WebP
     *
     * @param string $sourcePath
     * @param string $destinationPath
     * @param array $options
     * @return void
     */
    protected function optimizeImageAndConvert(string $sourcePath, string $destinationPath, array $options = []): void
    {
        try {
            $image = $this->imageManager->read($sourcePath);

            // Get configuration
            $maxWidth = $options['max_width'] ?? 1920;
            $quality = $options['quality'] ?? 80;

            // Resize down if width exceeds max (keep aspect ratio)
            if ($image->width() > $maxWidth) {
                $image->scaleDown(width: $maxWidth);
            }

            // Convert to WebP
            $bytes = $image->toWebp(quality: $quality);
            file_put_contents($destinationPath, $bytes);
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            \Log::warning("Image optimization failed: " . $e->getMessage());
            // Fallback: copy original file
            if (file_exists($sourcePath)) {
                copy($sourcePath, $destinationPath);
            }
        }
    }

    /**
     * Upload base64 image
     *
     * @param string $base64Data
     * @param string $directory
     * @param string|null $oldPath
     * @return array
     * @throws \Exception
     */
    public function uploadBase64Image(string $base64Data, string $directory = 'images', ?string $oldPath = null): array
    {
        // Decode base64
        $imageData = base64_decode($base64Data);
        if (!$imageData) {
            throw new \Exception("Invalid base64 image data");
        }

        // Create temporary file
        $tempPath = tempnam(sys_get_temp_dir(), 'image_upload_');
        file_put_contents($tempPath, $imageData);

        // Get MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tempPath);
        finfo_close($finfo);

        // Validate MIME type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mimeType, $allowedMimes)) {
            unlink($tempPath);
            throw new \Exception("Invalid image type");
        }

        // Check file size
        $fileSize = filesize($tempPath);
        if ($fileSize > $this->maxFileSize) {
            unlink($tempPath);
            throw new \Exception("File size must not exceed 5MB");
        }

        // Delete old image if provided
        if ($oldPath) {
            $this->deleteImage($oldPath);
        }

        // Generate webp filename
        $filename = now()->format('Y-m-d-His') . '-' . Str::random(10) . '.webp';
        $fullDirectory = "public/{$directory}";
        Storage::makeDirectory($fullDirectory);

        // Store and convert to webp
        $path = "{$directory}/{$filename}";
        $finalFullPath = Storage::path("public/{$path}");

        $this->optimizeImageAndConvert($tempPath, $finalFullPath);

        // Clean up temp file
        unlink($tempPath);

        return [
            'path' => $path,
            'url' => asset('storage/' . $path),
            'filename' => $filename,
            'size' => filesize($finalFullPath),
            'mime_type' => 'image/webp'
        ];
    }

    /**
     * Generate unique filename with given extension
     *
     * @param string $extension
     * @return string
     */
    protected function generateUniqueFilenameFromExtension(string $extension): string
    {
        $timestamp = now()->format('Y-m-d-His');
        $random = Str::random(10);

        return "{$timestamp}-{$random}.{$extension}";
    }

    /**
     * Create thumbnail from image
     *
     * @param string $sourcePath
     * @param int $width
     * @param int $height
     * @return string|null
     */
    public function createThumbnail(string $sourcePath, int $width = 300, int $height = 300): ?string
    {
        try {
            $image = $this->imageManager->read(Storage::disk('public')->path($sourcePath));

            // Create thumbnail with cover (crop to fit)
            $image->cover($width, $height);

            // Generate thumbnail filename
            $pathInfo = pathinfo($sourcePath);
            $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];

            // Save thumbnail
            $bytes = $image->toWebp(quality: 75);
            Storage::disk('public')->put($thumbnailPath, $bytes);

            return $thumbnailPath;
        } catch (\Exception $e) {
            \Log::warning("Thumbnail creation failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Compress image file with custom settings
     *
     * @param UploadedFile $file
     * @param array $options
     * @return array
     */
    public function compressAndUpload(UploadedFile $file, array $options = []): array
    {
        $directory = $options['directory'] ?? 'images';
        $quality = $options['quality'] ?? 75;
        $maxWidth = $options['max_width'] ?? 1920;
        $convertToWebp = $options['webp'] ?? true;
        $createThumbnail = $options['thumbnail'] ?? false;

        return $this->uploadImage(
            $file,
            $directory,
            $options['old_path'] ?? null
        );
    }
}