<?php

require __DIR__ . '/vendor/autoload.php';

use FFMpeg\FFMpeg;
use Psr\Log\AbstractLogger;
use Katzgrau\KLogger\Logger;

$logger = new Logger(__DIR__ . '/logs');

$ffmpeg = FFMpeg::create(
    [
        'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
        'ffprobe.binaries' => '/usr/local/bin/ffprobe',
        'timeout'          => 3600, // The timeout for the underlying process
        'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
    ],
    $logger
);

$dist = __DIR__ . '/dist/sample-0-1-2.mp4';

// Remove dist file if it exists to avoid failure on save.
if (file_exists($dist)) {
    unlink($dist);
}

$video = $ffmpeg->open(__DIR__ . '/src/sample-0.mp4');
$video
    ->concat(
        [
            __DIR__ . '/src/sample-0.mp4',
            __DIR__ . '/src/sample-1.mp4',
            __DIR__ . '/src/sample-2.mp4',
        ]
    )
    ->saveFromSameCodecs($dist, true);
