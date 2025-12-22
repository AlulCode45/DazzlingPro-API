<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $galleries = Gallery::query()
            ->when($request->has('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->has('featured'), function ($query) use ($request) {
                $query->where('is_featured', $request->boolean('featured'));
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Add full URL to images
        $galleries->transform(function ($gallery) {
            if ($gallery->featured_image && !str_starts_with($gallery->featured_image, 'http')) {
                $gallery->featured_image = url($gallery->featured_image);
            }
            if ($gallery->images) {
                $gallery->images = array_map(function ($img) {
                    return str_starts_with($img, 'http') ? $img : url($img);
                }, $gallery->images);
            }
            return $gallery;
        });

        return $this->sendResponse($galleries, 'Galleries retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:gallery_categories,id',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_featured' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        // Handle featured image upload
        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imageName = time() . '_featured_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/gallery'), $imageName);
            $featuredImagePath = 'uploads/gallery/' . $imageName;
        }

        // Handle multiple images upload
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/gallery'), $imageName);
                $imagePaths[] = 'uploads/gallery/' . $imageName;
            }
        }

        $gallery = Gallery::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'featured_image' => $featuredImagePath,
            'images' => $imagePaths,
            'is_featured' => $request->is_featured ?? false,
        ]);

        $gallery->load('category');

        // Add full URL to images
        if ($gallery->featured_image && !str_starts_with($gallery->featured_image, 'http')) {
            $gallery->featured_image = url($gallery->featured_image);
        }
        if ($gallery->images) {
            $gallery->images = array_map(function ($img) {
                return str_starts_with($img, 'http') ? $img : url($img);
            }, $gallery->images);
        }

        return $this->sendResponse($gallery, 'Gallery created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param Gallery $gallery
     * @return JsonResponse
     */
    public function show(Gallery $gallery): JsonResponse
    {
        $gallery->load('category');

        // Add full URL to images
        if ($gallery->featured_image && !str_starts_with($gallery->featured_image, 'http')) {
            $gallery->featured_image = url($gallery->featured_image);
        }
        if ($gallery->images) {
            $gallery->images = array_map(function ($img) {
                return str_starts_with($img, 'http') ? $img : url($img);
            }, $gallery->images);
        }

        return $this->sendResponse($gallery, 'Gallery retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Gallery $gallery
     * @return JsonResponse
     */
    public function update(Request $request, Gallery $gallery): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:gallery_categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'is_featured' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        $updateData = $request->only(['title', 'category_id', 'is_featured']);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old featured image
            if ($gallery->featured_image && file_exists(public_path($gallery->featured_image))) {
                @unlink(public_path($gallery->featured_image));
            }
            $image = $request->file('featured_image');
            $imageName = time() . '_featured_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/gallery'), $imageName);
            $updateData['featured_image'] = 'uploads/gallery/' . $imageName;
        }

        // Handle multiple images
        $existingImages = $request->existing_images ?? [];
        $newImagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/gallery'), $imageName);
                $newImagePaths[] = 'uploads/gallery/' . $imageName;
            }
        }

        // Combine existing and new images
        $updateData['images'] = array_merge($existingImages, $newImagePaths);

        $gallery->update($updateData);
        $gallery->load('category');

        // Add full URL to images
        if ($gallery->featured_image && !str_starts_with($gallery->featured_image, 'http')) {
            $gallery->featured_image = url($gallery->featured_image);
        }
        if ($gallery->images) {
            $gallery->images = array_map(function ($img) {
                return str_starts_with($img, 'http') ? $img : url($img);
            }, $gallery->images);
        }

        return $this->sendResponse($gallery, 'Gallery updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Gallery $gallery
     * @return JsonResponse
     */
    public function destroy(Gallery $gallery): JsonResponse
    {
        $gallery->delete();

        return $this->sendResponse([], 'Gallery deleted successfully.');
    }

    /**
     * Get gallery categories
     *
     * @return JsonResponse
     */
    public function categories(): JsonResponse
    {
        $categories = GalleryCategory::orderBy('name', 'asc')->get();

        return $this->sendResponse($categories, 'Gallery categories retrieved successfully.');
    }
}