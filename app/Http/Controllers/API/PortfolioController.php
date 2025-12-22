<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PortfolioController extends Controller
{
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
            'images.*' => 'url',
            'featured_image' => 'nullable|url',
            'featured' => 'sometimes|boolean',
            'completed' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
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
            'images' => $request->images ?? [],
            'featured_image' => $request->featured_image,
            'featured' => $request->featured ?? false,
            'completed' => $request->completed ?? false,
            'status' => $request->status ?? true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        $portfolio->load('category');

        return $this->sendResponse($portfolio, 'Portfolio created successfully.', 201);
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
            'images.*' => 'url',
            'featured_image' => 'nullable|url',
            'featured' => 'sometimes|boolean',
            'completed' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $portfolio->update($request->all());
        $portfolio->load('category');

        return $this->sendResponse($portfolio, 'Portfolio updated successfully.');
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