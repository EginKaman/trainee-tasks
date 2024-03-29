<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'title' => fake()->unique()->randomElement(
                ['QA', 'Team lead', 'Developer', 'Project manager', 'CTO', 'CEO']
            ),
        ];
    }
}
