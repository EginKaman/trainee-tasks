<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\{OrderProduct, OrderProductTranslation, Product, ProductTranslation};
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\{Schema, Storage};

class ProductsRemoveCommand extends Command
{
    protected $signature = 'products:remove';

    protected $description = 'Removed all products from database.';

    public function handle(): void
    {
        Product::whereNotNull('image')->chunk(20, function (Collection $products): void {
            Storage::delete($products->map(fn (Product $product) => $product->image)->toArray());
        });

        Schema::disableForeignKeyConstraints();

        OrderProductTranslation::truncate();
        OrderProduct::truncate();
        ProductTranslation::truncate();
        Product::truncate();

        Schema::enableForeignKeyConstraints();
    }
}
