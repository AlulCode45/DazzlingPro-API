<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
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
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('category_id'), function ($query) use ($request) {
                $query->where('gallery_category_id', $request->category_id);
            })
            ->when($request->has('featured'), function ($query) use ($request) {
                $query->where('featured', $request->boolean('featured'));
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->with(['category'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 12);

        return $this->sendResponseWithPagination($galleries, 'Galleries retrieved successfully.');
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
            'slug' => 'required|string|max:255|unique:galleries',
            'description' => 'nullable|string',
            'image_url' => 'required|url',
            'thumbnail_url' => 'nullable|url',
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'featured' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $gallery = Gallery::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'image_url' => $request->image_url,
            'thumbnail_url' => $request->thumbnail_url,
            'gallery_category_id' => $request->gallery_category_id,
            'featured' => $request->featured ?? false,
            'status' => $request->status ?? true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        $gallery->load('category');

        return $this->sendResponse($gallery, 'Gallery created successfully.', 201);
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
            'slug' => 'sometimes|required|string|max:255|unique:galleries,slug,' . $gallery->id,
            'description' => 'nullable|string',
            'image_url' => 'sometimes|required|url',
            'thumbnail_url' => 'nullable|url',
            'gallery_category_id' => 'sometimes|required|exists:gallery_categories,id',
            'featured' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $gallery->update($request->all());
        $gallery->load('category');

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
        $categories = GalleryCategory::where('status', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return $this->sendResponse($categories, 'Gallery categories retrieved successfully.');
    }
}