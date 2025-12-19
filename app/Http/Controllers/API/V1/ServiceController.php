<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $services = Service::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse(
            $services,
            'Services retrieved successfully.'
        );
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
            'slug' => 'required|string|max:255|unique:services,slug',
            'description' => 'required|string',
            'icon_url' => 'required|string|max:10',
            'full_description' => 'sometimes|string|nullable',
            'features' => 'sometimes|array',
            'packages' => 'sometimes|array',
            'image_url' => 'sometimes|string|nullable',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        $service = Service::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'icon_url' => $request->icon_url,
            'full_description' => $request->full_description,
            'features' => $request->features ?? [],
            'packages' => $request->packages ?? [],
            'image_url' => $request->image_url,
            'is_active' => $request->is_active ?? true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return $this->sendResponse($service, 'Service created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service): JsonResponse
    {
        return $this->sendResponse($service, 'Service retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:services,slug,' . $service->id,
            'description' => 'sometimes|required|string',
            'icon_url' => 'sometimes|required|string|max:10',
            'full_description' => 'sometimes|string|nullable',
            'features' => 'sometimes|array',
            'packages' => 'sometimes|array',
            'image_url' => 'sometimes|string|nullable',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        // Prepare update data
        $updateData = $request->only([
            'title',
            'slug',
            'description',
            'icon_url',
            'full_description',
            'features',
            'packages',
            'image_url',
            'is_active',
            'sort_order',
        ]);

        // Filter out null values to avoid overwriting with nulls
        $updateData = array_filter($updateData, function ($value) {
            return $value !== null;
        });

        $service->update($updateData);

        return $this->sendResponse($service, 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service): JsonResponse
    {
        $service->delete();

        return $this->sendResponse([], 'Service deleted successfully.');
    }
}