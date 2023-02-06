<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array<int, string>
     */
    protected $except = ['current_password', 'password', 'password_confirmation'];

    /**
     * Transform the given value.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function transform($key, $value): mixed
    {
        if (in_array($key, $this->except, true) || !is_string($value)) {
            return $value;
        }

        $value = preg_replace('~^[\s\x{FEFF}\x{200B}]+|[\s\x{FEFF}\x{200B}]+$~u', '', $value) ?? trim($value);
        $value = preg_replace('/[\r\n]{2,}+/m', "\r\n\r\n", $value);
        $value = preg_replace('/\n{2,}+/m', "\n\n", $value);

        return preg_replace('/ +/m', ' ', $value);
    }
}
