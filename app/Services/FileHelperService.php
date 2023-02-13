<?php

declare(strict_types=1);

namespace App\Services;

class FileHelperService
{
    public function sizeForHumans(float|int $size, int $precision = 2): string
    {
        $units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size /= $step;
            ++$i;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }
}
