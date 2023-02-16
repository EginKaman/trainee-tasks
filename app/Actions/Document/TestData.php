<?php

declare(strict_types=1);

namespace App\Actions\Document;

use Illuminate\Support\Str;

class TestData
{
    public function files(): array
    {
        $files = [
            'positive' => [],
            'negative' => []
        ];
        $examples = \Storage::disk('public')->allFiles('examples');

        foreach ($examples as $example) {
            if (Str::startsWith($example, 'examples/correct-')) {
                $files['positive'][] = [
                    'url' => \Storage::url($example),
                    'size' => round(\Storage::disk('public')->size($example) / 1024, 2),
                    'name' => Str::replace('examples/', '', $example)
                ];
            } elseif (Str::startsWith($example, ['examples/wrong-', 'examples/invalid-'])) {
                $files['negative'][] = [
                    'url' => \Storage::url($example),
                    'size' => round(\Storage::disk('public')->size($example) / 1024, 2),
                    'name' => Str::replace('examples/', '', $example)
                ];
            }

        }

        return $files;
    }
}
