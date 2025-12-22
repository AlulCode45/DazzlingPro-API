<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $partners = Partner::query()
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('partner_type'), function ($query) use ($request) {
                $query->where('partner_type', $request->partner_type);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10);

        return $this->sendResponseWithPagination($partners, 'Partners retrieved successfully.');
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
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:partners',
            'description' => 'nullable|string',
            'logo_url' => 'required|url',
            'website_url' => 'nullable|url',
            'partner_type' => 'required|string|in:sponsor,venue,vendor,media,other',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $partner = Partner::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'logo_url' => $request->logo_url,
            'website_url' => $request->website_url,
            'partner_type' => $request->partner_type,
            'status' => $request->status ?? true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return $this->sendResponse($partner, 'Partner created successfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Partner $partner
     * @return JsonResponse
     */
    public function show(Partner $partner): JsonResponse
    {
        return $this->sendResponse($partner, 'Partner retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Partner $partner
     * @return JsonResponse
     */
    public function update(Request $request, Partner $partner): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:partners,slug,' . $partner->id,
            'description' => 'nullable|string',
            'logo_url' => 'sometimes|required|url',
            'website_url' => 'nullable|url',
            'partner_type' => 'sometimes|required|string|in:sponsor,venue,vendor,media,other',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $partner->update($request->all());

        return $this->sendResponse($partner, 'Partner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Partner $partner
     * @return JsonResponse
     */
    public function destroy(Partner $partner): JsonResponse
    {
        $partner->delete();

        return $this->sendResponse([], 'Partner deleted successfully.');
    }

    /**
     * Get active partners by type
     *
     * @param string $type
     * @return JsonResponse
     */
    public function getByType(string $type): JsonResponse
    {
        if (!in_array($type, ['sponsor', 'venue', 'vendor', 'media', 'other'])) {
            return $this->sendError('Invalid partner type.', [], 400);
        }

        $partners = Partner::where('partner_type', $type)
            ->where('status', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return $this->sendResponse($partners, ucfirst($type) . ' partners retrieved successfully.');
    }

    /**
     * Get all active partners grouped by type
     *
     * @return JsonResponse
     */
    public function grouped(): JsonResponse
    {
        $partners = Partner::where('status', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->groupBy('partner_type');

        return $this->sendResponse($partners, 'Partners grouped by type retrieved successfully.');
    }
}