<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $testimonials = Testimonial::query()
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('rating'), function ($query) use ($request) {
                $query->where('rating', $request->rating);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10);

        return $this->sendResponseWithPagination($testimonials, 'Testimonials retrieved successfully.');
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
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image_url' => 'nullable|url',
            'status' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $testimonial = Testimonial::create([
            'name' => $request->name,
            'position' => $request->position,
            'company' => $request->company,
            'content' => $request->content,
            'rating' => $request->rating,
            'image_url' => $request->image_url,
            'status' => $request->status ?? true,
        ]);

        return $this->sendResponse($testimonial, 'Testimonial created successfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Testimonial $testimonial
     * @return JsonResponse
     */
    public function show(Testimonial $testimonial): JsonResponse
    {
        return $this->sendResponse($testimonial, 'Testimonial retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Testimonial $testimonial
     * @return JsonResponse
     */
    public function update(Request $request, Testimonial $testimonial): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'sometimes|required|string',
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'image_url' => 'nullable|url',
            'status' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $testimonial->update($request->all());

        return $this->sendResponse($testimonial, 'Testimonial updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Testimonial $testimonial
     * @return JsonResponse
     */
    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();

        return $this->sendResponse([], 'Testimonial deleted successfully.');
    }
}