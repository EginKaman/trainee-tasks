<?php

declare(strict_types=1);

namespace App\Services\Images;

use Carbon\CarbonTimeZone;
use Imagick;
use ImagickPixel;

class Annotate
{
    public function handle(Imagick $imagick, string $text): Imagick
    {
        $draw = new \ImagickDraw();
        $draw->setFillColor(new ImagickPixel('red'));
        $draw->setFont('Courier');
        $draw->setFillOpacity(0.5);
        $draw->setFontSize(30);
        $date = now()->setTimezone(
            CarbonTimeZone::createFromMinuteOffset((int) request()->post('timezone') * -1)
        )->format('Y-m-d H:i:s');
        for ($i = 1; $i <= 20; ++$i) {
            $imagick->annotateImage($draw, 10, $i * 50, -45, 'Copy ' . $date);
            $imagick->annotateImage($draw, 320, $i * 50, -45, 'Copy ' . $date);
        }

        return $imagick;
    }
}
