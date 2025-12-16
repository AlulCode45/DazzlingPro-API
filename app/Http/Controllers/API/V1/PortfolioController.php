<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PortfolioController extends Controller
{
    /**
     * @var FileUploadService
     */
    protected $fileUploadService;

    /**
     * Constructor
     */
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $portfolios = Portfolio::query()
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('category_id'), function ($query) use ($request) {
                $query->where('portfolio_category_id', $request->category_id);
            })
            ->when($request->has('featured'), function ($query) use ($request) {
                $query->where('featured', $request->boolean('featured'));
            })
            ->when($request->has('completed'), function ($query) use ($request) {
                $query->where('completed', $request->boolean('completed'));
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%')
                      ->orWhere('client_name', 'like', '%' . $request->search . '%');
            })
            ->when($request->has('event_date_from'), function ($query) use ($request) {
                $query->whereDate('event_date', '>=', $request->event_date_from);
            })
            ->when($request->has('event_date_to'), function ($query) use ($request) {
                $query->whereDate('event_date', '<=', $request->event_date_to);
            })
            ->with(['category'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 12);

        return $this->sendResponseWithPagination($portfolios, 'Portfolios retrieved successfully.');
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
            'slug' => 'required|string|max:255|unique:portfolios',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'client_name' => 'nullable|string|max:255',
            'event_date' => 'nullable|date',
            'event_location' => 'nullable|string|max:255',
            'portfolio_category_id' => 'required|exists:portfolio_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp|max:5120',
            'image_urls' => 'nullable|array',
            'image_urls.*' => 'url',
            'featured_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'featured_image_url' => 'nullable|url',
            'featured' => 'sometimes|boolean',
            'completed' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ], [
            'title.required' => 'Judul harus diisi',
            'title.string' => 'Judul harus berupa string',
            'title.max' => 'Judul maksimal 255 karakter',
            'slug.required' => 'Slug harus diisi',
            'slug.unique' => 'Slug sudah digunakan',
            'description.required' => 'Deskripsi harus diisi',
            'portfolio_category_id.required' => 'Kategori harus dipilih',
            'portfolio_category_id.exists' => 'Kategori tidak valid',
            'images.*.file' => 'Gambar harus berupa file',
            'images.*.mimes' => 'Format gambar tidak diizinkan. Gunakan: jpg, jpeg, png, webp',
            'images.*.max' => 'Ukuran gambar maksimal 5MB',
            'featured_image.file' => 'Gambar utama harus berupa file',
            'featured_image.mimes' => 'Format gambar utama tidak diizinkan. Gunakan: jpg, jpeg, png, webp',
            'featured_image.max' => 'Ukuran gambar utama maksimal 5MB',
            'sort_order.integer' => 'Urutan harus berupa angka',
            'sort_order.min' => 'Urutan minimal 0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        // Handle featured image upload
        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            try {
                $uploadResult = $this->fileUploadService->uploadImage(
                    $request->file('featured_image'),
                    'portfolios/featured'
                );
                $featuredImagePath = $uploadResult['path'];
            } catch (\Exception $e) {
                return $this->sendError('Gagal mengupload gambar utama: ' . $e->getMessage(), [], 422);
            }
        } elseif ($request->filled('featured_image_url')) {
            $featuredImagePath = $request->featured_image_url;
        }

        // Handle multiple images upload
        $imagePaths = [];

        // Process uploaded files
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $uploadResult = $this->fileUploadService->uploadImage(
                        $image,
                        'portfolios/images'
                    );
                    $imagePaths[] = $uploadResult['path'];
                } catch (\Exception $e) {
                    return $this->sendError('Gagal mengupload gambar: ' . $e->getMessage(), [], 422);
                }
            }
        }

        // Merge with URL images if any
        if ($request->has('image_urls')) {
            $imagePaths = array_merge($imagePaths, array_filter($request->image_urls));
        }

        $portfolio = Portfolio::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'client_name' => $request->client_name,
            'event_date' => $request->event_date,
            'event_location' => $request->event_location,
            'portfolio_category_id' => $request->portfolio_category_id,
            'images' => $imagePaths,
            'featured_image' => $featuredImagePath,
            'featured' => $request->featured ?? false,
            'completed' => $request->completed ?? false,
            'status' => $request->status ?? true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        $portfolio->load('category');

        return $this->sendResponse($portfolio, 'Portfolio berhasil dibuat.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Portfolio $portfolio
     * @return JsonResponse
     */
    public function show(Portfolio $portfolio): JsonResponse
    {
        $portfolio->load('category');

        return $this->sendResponse($portfolio, 'Portfolio retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Portfolio $portfolio
     * @return JsonResponse
     */
    public function update(Request $request, Portfolio $portfolio): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:portfolios,slug,' . $portfolio->id,
            'description' => 'sometimes|required|string',
            'short_description' => 'nullable|string|max:500',
            'client_name' => 'nullable|string|max:255',
            'event_date' => 'nullable|date',
            'event_location' => 'nullable|string|max:255',
            'portfolio_category_id' => 'sometimes|required|exists:portfolio_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp|max:5120',
            'image_urls' => 'nullable|array',
            'image_urls.*' => 'url',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'featured_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'featured_image_url' => 'nullable|url',
            'delete_featured_image' => 'sometimes|boolean',
            'featured' => 'sometimes|boolean',
            'completed' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ], [
            'title.required' => 'Judul harus diisi',
            'title.string' => 'Judul harus berupa string',
            'title.max' => 'Judul maksimal 255 karakter',
            'slug.required' => 'Slug harus diisi',
            'slug.unique' => 'Slug sudah digunakan',
            'description.required' => 'Deskripsi harus diisi',
            'portfolio_category_id.required' => 'Kategori harus dipilih',
            'portfolio_category_id.exists' => 'Kategori tidak valid',
            'images.*.file' => 'Gambar harus berupa file',
            'images.*.mimes' => 'Format gambar tidak diizinkan. Gunakan: jpg, jpeg, png, webp',
            'images.*.max' => 'Ukuran gambar maksimal 5MB',
            'featured_image.file' => 'Gambar utama harus berupa file',
            'featured_image.mimes' => 'Format gambar utama tidak diizinkan. Gunakan: jpg, jpeg, png, webp',
            'featured_image.max' => 'Ukuran gambar utama maksimal 5MB',
            'sort_order.integer' => 'Urutan harus berupa angka',
            'sort_order.min' => 'Urutan minimal 0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        // Handle featured image update
        $featuredImagePath = $portfolio->featured_image;

        if ($request->hasFile('featured_image')) {
            // Delete old featured image if exists
            if ($featuredImagePath) {
                $this->fileUploadService->deleteImage($featuredImagePath);
            }

            try {
                $uploadResult = $this->fileUploadService->uploadImage(
                    $request->file('featured_image'),
                    'portfolios/featured'
                );
                $featuredImagePath = $uploadResult['path'];
            } catch (\Exception $e) {
                return $this->sendError('Gagal mengupload gambar utama: ' . $e->getMessage(), [], 422);
            }
        } elseif ($request->boolean('delete_featured_image')) {
            // Delete featured image if requested
            if ($featuredImagePath) {
                $this->fileUploadService->deleteImage($featuredImagePath);
                $featuredImagePath = null;
            }
        } elseif ($request->filled('featured_image_url')) {
            $featuredImagePath = $request->featured_image_url;
        }

        // Handle images update
        $imagePaths = [];

        // Keep existing images if specified
        if ($request->has('existing_images')) {
            $imagePaths = array_filter($request->existing_images);
        }

        // Process new uploaded files
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $uploadResult = $this->fileUploadService->uploadImage(
                        $image,
                        'portfolios/images'
                    );
                    $imagePaths[] = $uploadResult['path'];
                } catch (\Exception $e) {
                    return $this->sendError('Gagal mengupload gambar: ' . $e->getMessage(), [], 422);
                }
            }
        }

        // Merge with URL images if any
        if ($request->has('image_urls')) {
            $imagePaths = array_merge($imagePaths, array_filter($request->image_urls));
        }

        $portfolio->update([
            'title' => $request->title ?? $portfolio->title,
            'slug' => $request->slug ?? $portfolio->slug,
            'description' => $request->description ?? $portfolio->description,
            'short_description' => $request->short_description ?? $portfolio->short_description,
            'client_name' => $request->client_name ?? $portfolio->client_name,
            'event_date' => $request->event_date ?? $portfolio->event_date,
            'event_location' => $request->event_location ?? $portfolio->event_location,
            'portfolio_category_id' => $request->portfolio_category_id ?? $portfolio->portfolio_category_id,
            'images' => $imagePaths,
            'featured_image' => $featuredImagePath,
            'featured' => $request->boolean('featured', $portfolio->featured),
            'completed' => $request->boolean('completed', $portfolio->completed),
            'status' => $request->boolean('status', $portfolio->status),
            'sort_order' => $request->sort_order ?? $portfolio->sort_order,
        ]);

        $portfolio->load('category');

        return $this->sendResponse($portfolio, 'Portfolio berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Portfolio $portfolio
     * @return JsonResponse
     */
    public function destroy(Portfolio $portfolio): JsonResponse
    {
        $portfolio->delete();

        return $this->sendResponse([], 'Portfolio deleted successfully.');
    }

    /**
     * Get portfolio categories
     *
     * @return JsonResponse
     */
    public function categories(): JsonResponse
    {
        $categories = PortfolioCategory::where('status', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return $this->sendResponse($categories, 'Portfolio categories retrieved successfully.');
    }

    /**
     * Get featured portfolios
     *
     * @return JsonResponse
     */
    public function featured(): JsonResponse
    {
        $portfolios = Portfolio::where('featured', true)
            ->where('status', true)
            ->with(['category'])
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        return $this->sendResponse($portfolios, 'Featured portfolios retrieved successfully.');
    }

}