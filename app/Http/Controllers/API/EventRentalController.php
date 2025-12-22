<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\Controller;
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
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('rental_type'), function ($query) use ($request) {
                $query->where('rental_type', $request->rental_type);
            })
            ->when($request->has('available'), function ($query) use ($request) {
                $query->where('available', $request->boolean('available'));
            })
            ->when($request->has('min_price'), function ($query) use ($request) {
                $query->where('price_per_hour', '>=', $request->min_price);
            })
            ->when($request->has('max_price'), function ($query) use ($request) {
                $query->where('price_per_hour', '<=', $request->max_price);
            })
            ->when($request->has('min_capacity'), function ($query) use ($request) {
                $query->where('capacity', '>=', $request->min_capacity);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%');
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10);

        return $this->sendResponseWithPagination($rentals, 'Event rentals retrieved successfully.');
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
            'slug' => 'required|string|max:255|unique:event_rentals',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'location' => 'required|string|max:255',
            'rental_type' => 'required|string|in:venue,equipment,decoration,lighting,sound,other',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'required|numeric|min:0',
            'price_per_day' => 'nullable|numeric|min:0',
            'features' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'thumbnail_url' => 'nullable|url',
            'availability_calendar' => 'nullable|array',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'available' => 'sometimes|boolean',
            'featured' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $rental = EventRental::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'location' => $request->location,
            'rental_type' => $request->rental_type,
            'capacity' => $request->capacity,
            'price_per_hour' => $request->price_per_hour,
            'price_per_day' => $request->price_per_day,
            'features' => $request->features ?? [],
            'images' => $request->images ?? [],
            'thumbnail_url' => $request->thumbnail_url,
            'availability_calendar' => $request->availability_calendar ?? [],
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'available' => $request->available ?? true,
            'featured' => $request->featured ?? false,
            'status' => $request->status ?? true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return $this->sendResponse($rental, 'Event rental created successfully.', 201);
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
            'slug' => 'sometimes|required|string|max:255|unique:event_rentals,slug,' . $eventRental->id,
            'description' => 'sometimes|required|string',
            'short_description' => 'nullable|string|max:500',
            'location' => 'sometimes|required|string|max:255',
            'rental_type' => 'sometimes|required|string|in:venue,equipment,decoration,lighting,sound,other',
            'capacity' => 'sometimes|required|integer|min:1',
            'price_per_hour' => 'sometimes|required|numeric|min:0',
            'price_per_day' => 'nullable|numeric|min:0',
            'features' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'thumbnail_url' => 'nullable|url',
            'availability_calendar' => 'nullable|array',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'available' => 'sometimes|boolean',
            'featured' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $eventRental->update($request->all());

        return $this->sendResponse($eventRental, 'Event rental updated successfully.');
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
     * Get available rentals by type
     *
     * @param string $type
     * @return JsonResponse
     */
    public function getByType(string $type): JsonResponse
    {
        if (!in_array($type, ['venue', 'equipment', 'decoration', 'lighting', 'sound', 'other'])) {
            return $this->sendError('Invalid rental type.', [], 400);
        }

        $rentals = EventRental::where('rental_type', $type)
            ->where('available', true)
            ->where('status', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return $this->sendResponse($rentals, ucfirst($type) . ' rentals retrieved successfully.');
    }

    /**
     * Get featured rentals
     *
     * @return JsonResponse
     */
    public function featured(): JsonResponse
    {
        $rentals = EventRental::where('featured', true)
            ->where('available', true)
            ->where('status', true)
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        return $this->sendResponse($rentals, 'Featured event rentals retrieved successfully.');
    }

    /**
     * Check availability for dates
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'rental_id' => 'nullable|exists:event_rentals,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $query = EventRental::where('available', true)
            ->where('status', true);

        if ($request->has('rental_id')) {
            $query->where('id', $request->rental_id);
        }

        // Check availability based on calendar
        $rentals = $query->get()->filter(function ($rental) use ($request) {
            if (!$rental->availability_calendar || empty($rental->availability_calendar)) {
                return true; // Assume available if no calendar is set
            }

            // Check if dates are available in calendar
            $requestedDates = $this->getDatesBetween($request->start_date, $request->end_date);
            foreach ($requestedDates as $date) {
                if (in_array($date, $rental->availability_calendar)) {
                    return false; // Date is booked
                }
            }

            return true; // All requested dates are available
        });

        return $this->sendResponse($rentals->values(), 'Available rentals retrieved successfully.');
    }

    /**
     * Helper method to get all dates between two dates
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function getDatesBetween(string $startDate, string $endDate): array
    {
        $dates = [];
        $current = strtotime($startDate);
        $end = strtotime($endDate);

        while ($current <= $end) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }
}