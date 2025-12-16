<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Models\EventRental;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class EventRentalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $rentals = EventRental::query()
            ->when($request->has('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->when($request->has('featured'), function ($query) use ($request) {
                $query->where('is_featured', $request->boolean('featured'));
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse($rentals, 'Event rentals retrieved successfully.');
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
            'category' => 'required|string|max:255',
            'specifications' => 'required|string',
            'price_per_day' => 'required|numeric|min:0',
            'is_featured' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        $rental = EventRental::create([
            'name' => $request->name,
            'category' => $request->category,
            'specifications' => $request->specifications,
            'price_per_day' => $request->price_per_day,
            'is_featured' => $request->is_featured ?? false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $rental,
            'message' => 'Event rental created successfully.',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param EventRental $eventRental
     * @return JsonResponse
     */
    public function show(EventRental $eventRental): JsonResponse
    {
        return $this->sendResponse($eventRental, 'Event rental retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param EventRental $eventRental
     * @return JsonResponse
     */
    public function update(Request $request, EventRental $eventRental): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'specifications' => 'sometimes|required|string',
            'price_per_day' => 'sometimes|required|numeric|min:0',
            'is_featured' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        $eventRental->update($request->only(['name', 'category', 'specifications', 'price_per_day', 'is_featured']));

        return response()->json([
            'success' => true,
            'data' => $eventRental,
            'message' => 'Event rental updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param EventRental $eventRental
     * @return JsonResponse
     */
    public function destroy(EventRental $eventRental): JsonResponse
    {
        $eventRental->delete();

        return $this->sendResponse([], 'Event rental deleted successfully.');
    }

    /**
     * Get featured rentals
     *
     * @return JsonResponse
     */
    public function featured(): JsonResponse
    {
        $rentals = EventRental::where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse($rentals, 'Featured event rentals retrieved successfully.');
    }

    /**
     * Get rentals by type
     *
     * @param string $type
     * @return JsonResponse
     */
    public function getByType(string $type): JsonResponse
    {
        $rentals = EventRental::where('category', $type)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse($rentals, ucfirst($type) . ' rentals retrieved successfully.');
    }
}