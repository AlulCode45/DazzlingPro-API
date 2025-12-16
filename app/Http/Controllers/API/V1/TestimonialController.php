<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\API\V1\StoreTestimonialRequest;
use App\Http\Requests\API\V1\UpdateTestimonialRequest;
use App\Http\Requests\API\V1\GetTestimonialsRequest;
use App\Http\Resources\API\V1\TestimonialResource;
use App\Http\Resources\API\V1\TestimonialCollection;
use App\Services\Contracts\TestimonialServiceInterface;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    private TestimonialServiceInterface $testimonialService;

    public function __construct(TestimonialServiceInterface $testimonialService)
    {
        $this->testimonialService = $testimonialService;
    }

    public function index(GetTestimonialsRequest $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        $filters = $request->only(['status', 'rating']);

        $testimonials = $this->testimonialService->paginate($perPage, $filters);

        return $this->sendResponseWithPagination(
            new TestimonialCollection($testimonials),
            'Testimonials retrieved successfully.'
        );
    }

    public function store(StoreTestimonialRequest $request): JsonResponse
    {
        $testimonial = $this->testimonialService->create($request->validated());

        return $this->sendResponse(
            new TestimonialResource($testimonial),
            'Testimonial created successfully.',
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $testimonial = $this->testimonialService->getByIdOrFail($id);

        return $this->sendResponse(
            new TestimonialResource($testimonial),
            'Testimonial retrieved successfully.'
        );
    }

    public function update(UpdateTestimonialRequest $request, int $id): JsonResponse
    {
        $testimonial = $this->testimonialService->update($id, $request->validated());

        return $this->sendResponse(
            new TestimonialResource($testimonial),
            'Testimonial updated successfully.'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $this->testimonialService->delete($id);

        return $this->sendResponse(
            [],
            'Testimonial deleted successfully.'
        );
    }

    public function approve(int $id): JsonResponse
    {
        $testimonial = $this->testimonialService->approveTestimonial($id);

        return $this->sendResponse(
            new TestimonialResource($testimonial),
            'Testimonial approved successfully.'
        );
    }

    public function reject(int $id): JsonResponse
    {
        $testimonial = $this->testimonialService->rejectTestimonial($id);

        return $this->sendResponse(
            new TestimonialResource($testimonial),
            'Testimonial rejected successfully.'
        );
    }

    public function statistics(): JsonResponse
    {
        $statistics = $this->testimonialService->getTestimonialStatistics();

        return $this->sendResponse(
            $statistics,
            'Testimonial statistics retrieved successfully.'
        );
    }

    public function active(): JsonResponse
    {
        $testimonials = $this->testimonialService->getActiveTestimonials();

        return $this->sendResponse(
            TestimonialResource::collection($testimonials),
            'Active testimonials retrieved successfully.'
        );
    }

    public function byRating(int $rating): JsonResponse
    {
        $testimonials = $this->testimonialService->getTestimonialsByRating($rating);

        return $this->sendResponse(
            TestimonialResource::collection($testimonials),
            "Testimonials with rating {$rating} retrieved successfully."
        );
    }
}