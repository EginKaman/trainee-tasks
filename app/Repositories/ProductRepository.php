<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public static function getProductsWithPagination(int $per_page, int $page): LengthAwarePaginator
    {
        return Product::query()->withTranslation()->paginate(perPage: $per_page, page: $page);
    }
}
