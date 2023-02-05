<?php

namespace App\Services\Processing\Validator\Fields;

use App\Services\Processing\Validator\Error;

class Name
{
    public bool $break = false;
    public function correct(string $value, $line)
    {
        if (!preg_match('/^[A-Za-z ]{2,60}$/', $value)) {
            return new Error(
                "The name must be smaller include only latin symbols, space and between 2 to 60 letters.",
                $line
            );
        }
        return true;
    }
}
