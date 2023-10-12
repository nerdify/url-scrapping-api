<?php
if (!function_exists('get_interval_in_minutes')) {
    function get_interval_in_minutes(int $interval, string $type): int
    {
        return match ($type) {
            'hour' => $interval * 60,
            'day' => $interval * 1440,
            'week' => $interval * 10080,
            'month' => $interval * 43800,
            'year' => $interval * now()->isLeapYear() ? 527040 : 525600,
            default => $interval
        };
    }
}
