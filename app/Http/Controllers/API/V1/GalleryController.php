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
            if ($gallery->image_url && !str_starts_with($gallery->image_url, 'http')) {
                $gallery->image_url = url($gallery->image_url);
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
            'is_featured' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        // Handle file upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/gallery'), $imageName);
            $imagePath = 'uploads/gallery/' . $imageName;
        }

        $gallery = Gallery::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'image_url' => $imagePath ?? $request->image_url,
            'is_featured' => $request->is_featured ?? false,
        ]);

        $gallery->load('category');

        // Add full URL to image
        if ($gallery->image_url && !str_starts_with($gallery->image_url, 'http')) {
            $gallery->image_url = url($gallery->image_url);
        }

        return response()->json([
            'success' => true,
            'data' => $gallery,
            'message' => 'Gallery created successfully.',
        ], 201);
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

        // Add full URL to image
        if ($gallery->image_url && !str_starts_with($gallery->image_url, 'http')) {
            $gallery->image_url = url($gallery->image_url);
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
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
            'is_featured' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        $updateData = $request->only(['title', 'category_id', 'is_featured']);

        // Handle file upload if present
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/gallery'), $imageName);
            $updateData['image_url'] = 'uploads/gallery/' . $imageName;
        }

        $gallery->update($updateData);
        $gallery->load('category');

        // Add full URL to image
        if ($gallery->image_url && !str_starts_with($gallery->image_url, 'http')) {
            $gallery->image_url = url($gallery->image_url);
        }

        return response()->json([
            'success' => true,
            'data' => $gallery,
            'message' => 'Gallery updated successfully.',
        ]);
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