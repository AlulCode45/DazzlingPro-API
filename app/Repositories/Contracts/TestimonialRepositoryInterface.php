<?php

namespace App\Repositories\Contracts;

use App\Models\Testimonial;
use Illuminate\Support\Collection;

interface TestimonialRepositoryInterface extends BaseRepositoryInterface
{
    public function findByStatus(bool $status): Collection;
    public function findByRating(int $rating): Collection;
    public function getActiveTestimonials(): Collection;
    public function getTestimonialsByRatingRange(int $min, int $max): Collection;
}