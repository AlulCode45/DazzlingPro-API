<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Api\V1\StoreTestimonialRequest;
use App\Http\Requests\Api\V1\UpdateTestimonialRequest;
use App\Http\Requests\Api\V1\GetTestimonialsRequest;
use App\Http\Resources\Api\V1\TestimonialResource;
use App\Http\Resources\Api\V1\TestimonialCollection;
use App\Services\Contracts\TestimonialServiceInterface;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    private TestimonialServiceInterface $testimonialService;
    private FileUploadService $fileUploadService;

    public function __construct(
        TestimonialServiceInterface $testimonialService,
        FileUploadService $fileUploadService
    ) {
        $this->testimonialService = $testimonialService;
        $this->fileUploadService = $fileUploadService;
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
        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('image')) {
            try {
                $uploadResult = $this->fileUploadService->uploadImage(
                    $request->file('image'),
                    'testimonials'
                );
                $data['image_url'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return $this->sendError('Failed to upload image: ' . $e->getMessage(), [], 422);
            }
        }

        // Remove 'image' from data as we've processed it
        unset($data['image']);

        $testimonial = $this->testimonialService->create($data);

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
        $data = $request->validated();
        $testimonial = $this->testimonialService->getByIdOrFail($id);

        // Handle file upload
        if ($request->hasFile('image')) {
            try {
                // Delete old image if exists
                if ($testimonial->image_url) {
                    $this->fileUploadService->deleteImage($testimonial->image_url);
                }

                $uploadResult = $this->fileUploadService->uploadImage(
                    $request->file('image'),
                    'testimonials'
                );
                $data['image_url'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return $this->sendError('Failed to upload image: ' . $e->getMessage(), [], 422);
            }
        }

        // Remove 'image' from data as we've processed it
        unset($data['image']);

        $testimonial = $this->testimonialService->update($id, $data);

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