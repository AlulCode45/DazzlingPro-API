<?php

namespace App\Services\Contracts;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TestimonialServiceInterface extends BaseServiceInterface
{
    public function getActiveTestimonials(): Collection;
    public function getTestimonialsByRating(int $rating): Collection;
    public function getTestimonialsByRatingRange(int $min, int $max): Collection;
    public function approveTestimonial(int $id): Testimonial;
    public function rejectTestimonial(int $id): Testimonial;
    public function getTestimonialStatistics(): array;
}