<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Site;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class BuildingMenuListener
{
    public function __construct()
    {
    }

    public function handle(BuildingMenu $event): void
    {
        Site::with(['categories'])->each(function (Site $site) use ($event): void {
            $event->menu->add([
                'text' => $site->title,
                'submenu' => $site->categories->map(function ($category) use ($site): array {
                    return [
                        'text' => $category->title,
                        'url' => route('sites.categories.bots.index', [
                            'site' => $site, 'category' => $category,
                        ]),
                        'shift' => 'ml-4',
                    ];
                })->toArray(),
            ]);
        });
    }
}
