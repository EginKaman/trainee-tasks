<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Product;

class ProductController extends Controller
{
    public function __invoke(): ProductCollection
    {
        return new ProductCollection(Product::withTranslation()->get());
    }
}
