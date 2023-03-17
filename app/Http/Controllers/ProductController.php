<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    public function __invoke(ProductRequest $request): ProductCollection
    {
        $per_page = $request->validated('per_page', 6) ?? 6;
        $page = $request->validated('page', 1) ?? 1;

        return new ProductCollection(ProductRepository::getProductsWithPagination($per_page, $page));
    }
}
