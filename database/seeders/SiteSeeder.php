<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\{Bot, Category, Job, Site, Worker};
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->withProgressBar(Site::factory()->count(5)->create(), function ($site): void {
            $site->categories()->createMany(Category::factory()->count(5)->make()->toArray());
            $site->categories->each(function (Category $category): void {
                $category->bots()->createMany(Bot::factory()->count(random_int(0, 20))->make()->toArray());
                $category->bots->each(function (Bot $bot): void {
                    $bot->jobs()->createMany(Job::factory()->count(random_int(0, 30))->make()->toArray());
                    $bot->jobs->each(function (Job $job): void {
                        $job->workers()->createMany(
                            Worker::factory()->count($job->count_workers)->make()->toArray()
                        );
                    });
                });
            });
        });
    }
}
