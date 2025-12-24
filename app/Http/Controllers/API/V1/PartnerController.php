<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Models\Partner;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    protected $fileUploadService;

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
        $partners = Partner::query()
            ->when($request->has('type'), function ($query) use ($request) {
                $query->where('partner_type', $request->type);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse($partners, 'Partners retrieved successfully.');
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
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website_url' => 'nullable|url',
            'partner_type' => 'sometimes|required|string|in:sponsor,venue,vendor,media,other',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        // Handle file upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            try {
                $uploadResult = $this->fileUploadService->uploadImage(
                    $request->file('logo'),
                    'partners'
                );
                $logoPath = $uploadResult['path'];
            } catch (\Exception $e) {
                return $this->sendError('Failed to upload logo: ' . $e->getMessage(), [], 422);
            }
        }

        $partner = Partner::create([
            'name' => $request->name,
            'slug' => strtolower(str_replace(' ', '-', $request->name)) . '-' . time(),
            'logo_url' => $logoPath,
            'website_url' => $request->website_url,
            'partner_type' => $request->partner_type ?? 'sponsor',
            'status' => $request->status ?? true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'data' => $partner,
            'message' => 'Partner created successfully.',
        ], 201);
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
            'logo' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website_url' => 'nullable|url',
            'partner_type' => 'sometimes|required|string|in:sponsor,venue,vendor,media,other',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        $updateData = [];

        // Handle file upload if new logo is provided
        if ($request->hasFile('logo')) {
            try {
                $uploadResult = $this->fileUploadService->uploadImage(
                    $request->file('logo'),
                    'partners',
                    $partner->logo_url
                );
                $updateData['logo_url'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return $this->sendError('Failed to upload logo: ' . $e->getMessage(), [], 422);
            }
        }

        $updateData = array_merge($updateData, $request->only(['name', 'website_url', 'partner_type', 'status', 'sort_order']));

        $partner->update($updateData);

        return response()->json([
            'success' => true,
            'data' => $partner,
            'message' => 'Partner updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Partner $partner
     * @return JsonResponse
     */
    public function destroy(Partner $partner): JsonResponse
    {
        // Delete logo file if exists
        if ($partner->logo_url && file_exists(public_path($partner->logo_url))) {
            unlink(public_path($partner->logo_url));
        }

        $partner->delete();

        return $this->sendResponse([], 'Partner deleted successfully.');
    }

    /**
     * Get partners by type
     *
     * @param string $type
     * @return JsonResponse
     */
    public function getByType(string $type): JsonResponse
    {
        $partners = Partner::where('partner_type', $type)
            ->where('status', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return $this->sendResponse($partners, ucfirst($type) . ' partners retrieved successfully.');
    }

    /**
     * Get all partners grouped by type
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