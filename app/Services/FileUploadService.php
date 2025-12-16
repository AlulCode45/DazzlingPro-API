<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileUploadService
{
    /**
     * Allowed image extensions
     */
    protected array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * Maximum file size in bytes (5MB)
     */
    protected int $maxFileSize = 5 * 1024 * 1024;

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

        // Generate unique filename
        $filename = $this->generateUniqueFilename($file);

        // Create directory if it doesn't exist
        $fullDirectory = "public/{$directory}";
        Storage::makeDirectory($fullDirectory);

        // Store the file
        $path = $file->storeAs($directory, $filename, 'public');

        // Optimize image if it's a valid image
        $this->optimizeImage(Storage::path("public/{$path}"));

        // Get public URL
        $url = Storage::url($path);

        return [
            'path' => $path,
            'url' => $url,
            'filename' => $filename,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
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
     * Optimize image for web
     *
     * @param string $filePath
     * @return void
     */
    protected function optimizeImage(string $filePath): void
    {
        try {
            $image = Image::make($filePath);

            // Resize if width is greater than 1920px
            if ($image->width() > 1920) {
                $image->resize(1920, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Compress image with 85% quality
            $image->save($filePath, 85);
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            \Log::warning("Image optimization failed: " . $e->getMessage());
        }
    }

    /**
     * Get file info from URL or path
     *
     * @param string $pathOrUrl
     * @return array|null
     */
    public function getFileInfo(string $pathOrUrl): ?array
    {
        // Convert URL to path if needed
        $path = str_replace('/storage', '', $pathOrUrl);
        $path = ltrim($path, '/');

        if (Storage::disk('public')->exists($path)) {
            $fullPath = Storage::disk('public')->path($path);
            if (file_exists($fullPath)) {
                $imageInfo = getimagesize($fullPath);
                $fileSize = filesize($fullPath);

                return [
                    'path' => $path,
                    'url' => Storage::url($path),
                    'size' => $fileSize,
                    'width' => $imageInfo[0] ?? null,
                    'height' => $imageInfo[1] ?? null,
                    'mime_type' => mime_content_type($fullPath)
                ];
            }
        }

        return null;
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

        // Generate filename based on MIME type
        $extension = match($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg'
        };

        $filename = $this->generateUniqueFilenameFromExtension($extension);
        $fullDirectory = "public/{$directory}";
        Storage::makeDirectory($fullDirectory);

        // Store the file
        $path = "{$directory}/{$filename}";
        Storage::disk('public')->put($path, $imageData);

        // Clean up temp file
        unlink($tempPath);

        // Optimize the stored image
        $this->optimizeImage(Storage::disk('public')->path($path));

        return [
            'path' => $path,
            'url' => Storage::url($path),
            'filename' => $filename,
            'size' => $fileSize,
            'mime_type' => $mimeType
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
}