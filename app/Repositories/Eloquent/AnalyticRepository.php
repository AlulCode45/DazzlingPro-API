<?php

namespace App\Repositories\Eloquent;

use App\Models\Analytic;
use App\Repositories\Contracts\AnalyticRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticRepository extends BaseRepository implements AnalyticRepositoryInterface
{
    public function __construct(Analytic $model)
    {
        parent::__construct($model);
    }

    public function getPageViews(string $startDate = null, string $endDate = null): array
    {
        $query = $this->model->where('event_type', 'page_view');

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate));
        }
        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate));
        }

        return [
            'total' => $query->count(),
            'unique_sessions' => $query->distinct('session_id')->count('session_id'),
        ];
    }

    public function getTopPages(int $limit = 10): array
    {
        return $this->model
            ->select('page_url', 'page_title', DB::raw('count(*) as views'))
            ->where('event_type', 'page_view')
            ->whereNotNull('page_url')
            ->groupBy('page_url', 'page_title')
            ->orderByDesc('views')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getTotalVisitors(string $startDate = null, string $endDate = null): int
    {
        $query = $this->model->where('event_type', 'page_view');

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate));
        }
        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate));
        }

        return $query->distinct('session_id')->count('session_id');
    }

    public function getVisitorsByDate(string $startDate = null, string $endDate = null): array
    {
        $query = $this->model
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(DISTINCT session_id) as visitors'))
            ->where('event_type', 'page_view');

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate));
        }
        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate));
        }

        return $query
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->toArray();
    }

    public function getEventsByType(string $eventType, string $startDate = null, string $endDate = null): array
    {
        $query = $this->model->where('event_type', $eventType);

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate));
        }
        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate));
        }

        return $query->orderBy('created_at', 'desc')->get()->toArray();
    }

    public function getUniqueSessionCount(string $startDate = null, string $endDate = null): int
    {
        $query = $this->model;

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate));
        }
        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate));
        }

        return $query->distinct('session_id')->count('session_id');
    }

    public function getTopReferrers(int $limit = 10): array
    {
        return $this->model
            ->select('referrer', DB::raw('count(*) as visits'))
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->groupBy('referrer')
            ->orderByDesc('visits')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getBrowserStats(): array
    {
        $analytics = $this->model
            ->whereNotNull('user_agent')
            ->select('user_agent')
            ->get();

        $browsers = [];
        foreach ($analytics as $analytic) {
            $browser = $this->parseBrowser($analytic->user_agent);
            if (!isset($browsers[$browser])) {
                $browsers[$browser] = 0;
            }
            $browsers[$browser]++;
        }

        arsort($browsers);
        return $browsers;
    }

    public function trackEvent(array $data): bool
    {
        try {
            $this->model->create($data);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function parseBrowser(string $userAgent): string
    {
        if (stripos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (stripos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (stripos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (stripos($userAgent, 'Edge') !== false) {
            return 'Edge';
        } elseif (stripos($userAgent, 'Opera') !== false) {
            return 'Opera';
        } else {
            return 'Other';
        }
    }
}
