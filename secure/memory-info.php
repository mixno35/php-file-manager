<?php
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");

$monitoringTime = 60;
$monitoringInterval = 1;
$startTime = time();

while (time() - $startTime <= $monitoringTime) {
    $currentMemoryUsage = memory_get_usage();
    $peakMemoryUsage = memory_get_peak_usage(true);
    $currentTime = time();

    $data = [
        "time" => $currentTime,
        "memory_usage" => $currentMemoryUsage,
        "peak_usage" => $peakMemoryUsage
    ];

    echo "data: " . json_encode($data) . "\n\n";
    ob_flush();
    flush();

    sleep($monitoringInterval);
}