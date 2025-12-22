<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $services = Service::query()
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('featured'), function ($query) use ($request) {
                $query->where('featured', $request->boolean('featured'));
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10);

        return $this->sendResponseWithPagination($services, 'Services retrieved successfully.');
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
            'slug' => 'required|string|max:255|unique:services',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'featured' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $service = Service::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'icon' => $request->icon,
            'image_url' => $request->image_url,
            'featured' => $request->featured ?? false,
            'status' => $request->status ?? true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return $this->sendResponse($service, 'Service created successfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Service $service
     * @return JsonResponse
     */
    public function show(Service $service): JsonResponse
    {
        return $this->sendResponse($service, 'Service retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Service $service
     * @return JsonResponse
     */
    public function update(Request $request, Service $service): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:services,slug,' . $service->id,
            'description' => 'sometimes|required|string',
            'short_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'featured' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $service->update($request->all());

        return $this->sendResponse($service, 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Service $service
     * @return JsonResponse
     */
    public function destroy(Service $service): JsonResponse
    {
        $service->delete();

        return $this->sendResponse([], 'Service deleted successfully.');
    }
}