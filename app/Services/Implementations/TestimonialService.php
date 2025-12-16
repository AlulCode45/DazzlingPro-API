<?php

namespace App\Services\Implementations;

use App\Services\Contracts\TestimonialServiceInterface;
use App\Repositories\Contracts\TestimonialRepositoryInterface;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class TestimonialService extends BaseService implements TestimonialServiceInterface
{
    private TestimonialRepositoryInterface $testimonialRepository;

    public function __construct(TestimonialRepositoryInterface $testimonialRepository)
    {
        parent::__construct($testimonialRepository);
        $this->testimonialRepository = $testimonialRepository;
    }

    public function getActiveTestimonials(): Collection
    {
        return Cache::remember('active_testimonials', 3600, function () {
            return $this->testimonialRepository->getActiveTestimonials();
        });
    }

    public function getTestimonialsByRating(int $rating): Collection
    {
        return $this->testimonialRepository->findByRating($rating);
    }

    public function getTestimonialsByRatingRange(int $min, int $max): Collection
    {
        return $this->testimonialRepository->getTestimonialsByRatingRange($min, $max);
    }

    public function approveTestimonial(int $id): Testimonial
    {
        $testimonial = $this->testimonialRepository->findOrFail($id);
        $testimonial->update(['status' => true]);

        Cache::forget('active_testimonials');

        return $testimonial;
    }

    public function rejectTestimonial(int $id): Testimonial
    {
        $testimonial = $this->testimonialRepository->findOrFail($id);
        $testimonial->update(['status' => false]);

        Cache::forget('active_testimonials');

        return $testimonial;
    }

    public function create(array $data): Testimonial
    {
        $testimonial = $this->testimonialRepository->create($data);

        Cache::forget('active_testimonials');

        return $testimonial;
    }

    public function update(int $id, array $data): Testimonial
    {
        $testimonial = $this->testimonialRepository->update($id, $data);

        Cache::forget('active_testimonials');

        return $testimonial;
    }

    public function getTestimonialStatistics(): array
    {
        return Cache::remember('testimonial_statistics', 3600, function () {
            $testimonials = $this->testimonialRepository->all();

            return [
                'total' => $testimonials->count(),
                'active' => $testimonials->where('status', true)->count(),
                'inactive' => $testimonials->where('status', false)->count(),
                'average_rating' => round($testimonials->avg('rating'), 2),
                'rating_distribution' => $testimonials->groupBy('rating')
                    ->map->count()
                    ->sortKeys()
            ];
        });
    }
}