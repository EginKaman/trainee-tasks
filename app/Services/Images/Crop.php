<?php

declare(strict_types=1);

namespace App\Services\Images;

use Imagick;
use ImagickException;

class Crop
{
    /**
     * @throws ImagickException
     */
    public function handle(Imagick $imagick, int $width, int $height): Imagick
    {
        $imagick = $this->autoRotateImage($imagick);

        $imagick->thumbnailImage($width, $height);

        return $imagick;
    }

    public function autoRotateImage(Imagick $imagick): Imagick
    {
        $orientation = $imagick->getImageOrientation();

        switch ($orientation) {
            case Imagick::ORIENTATION_BOTTOMRIGHT:
                $imagick->rotateimage('#000', 180); // rotate 180 degrees

                break;

            case Imagick::ORIENTATION_RIGHTTOP:
                $imagick->rotateimage('#000', 90); // rotate 90 degrees CW

                break;

            case Imagick::ORIENTATION_LEFTBOTTOM:
                $imagick->rotateimage('#000', -90); // rotate 90 degrees CCW

                break;
            case Imagick::ORIENTATION_RIGHTBOTTOM:
                $imagick->rotateimage('#000', -90); // rotate 90 degrees CCW

                break;
        }

        // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
        $imagick->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);

        return $imagick;
    }
}
