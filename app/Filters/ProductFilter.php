<?php

namespace App\Filters;

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProductFilter
{
    public function apply(Builder $query, array $data): Builder
    {
        return $query
            ->when(!empty($data['category_id']), fn($q) => $q->where('category_id', $data['category_id']))
            ->when(isset($data['price_min']), fn($q) => $q->where('price', '>=', (float) $data['price_min']))
            ->when(isset($data['price_max']), fn($q) => $q->where('price', '<=', (float) $data['price_max']))
            ->when(!empty($data['name']), fn($q) => $q->where('name', 'like', '%' . $data['name'] . '%'))
            ->when(!empty($data['price_sort']), fn($q) => $q->orderBy('price', $data['price_sort']))
            ->when(!empty($data['created_at_sort']), fn($q) => $q->orderBy('created_at', $data['created_at_sort']))
            ->when(empty($data['price_sort']) && empty($data['created_at_sort']), fn($q) => $q->orderBy('id', 'desc'));
    }
}
