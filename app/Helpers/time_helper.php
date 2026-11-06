<?php

if (!function_exists('format_duration')) {
    function format_duration(int $seconds): string
    {
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        $s = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }
}

if (!function_exists('format_rupiah')) {
    function format_rupiah(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('calc_value')) {
    function calc_value(int $totalSeconds, int $hourlyRate): float
    {
        return round(($totalSeconds / 3600) * $hourlyRate);
    }
}
