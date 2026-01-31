<?php

namespace App\Repositories\Contracts;

interface AnalyticRepositoryInterface extends BaseRepositoryInterface
{
    public function getPageViews(string $startDate = null, string $endDate = null): array;
    public function getTopPages(int $limit = 10): array;
    public function getTotalVisitors(string $startDate = null, string $endDate = null): int;
    public function getVisitorsByDate(string $startDate = null, string $endDate = null): array;
    public function getEventsByType(string $eventType, string $startDate = null, string $endDate = null): array;
    public function getUniqueSessionCount(string $startDate = null, string $endDate = null): int;
    public function getTopReferrers(int $limit = 10): array;
    public function getBrowserStats(): array;
    public function trackEvent(array $data): bool;
}
