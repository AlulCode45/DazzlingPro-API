<?php

namespace App\Repositories\Eloquent;

use App\Models\Testimonial;
use App\Repositories\Contracts\TestimonialRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class TestimonialRepository extends BaseRepository implements TestimonialRepositoryInterface
{
    public function __construct(Testimonial $testimonial)
    {
        parent::__construct($testimonial);
    }

    public function findByStatus(bool $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function findByRating(int $rating): Collection
    {
        return $this->model->where('rating', $rating)->get();
    }

    public function getActiveTestimonials(): Collection
    {
        return $this->model->where('status', true)
            ->orderBy('id', 'asc')
            ->get();
    }

    public function getTestimonialsByRatingRange(int $min, int $max): Collection
    {
        return $this->model->whereBetween('rating', [$min, $max])
            ->orderBy('rating', 'desc')
            ->get();
    }

    public function paginate(int $perPage = 100, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->query;

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        return $query->orderBy('id', 'asc')->paginate($perPage);
    }
}