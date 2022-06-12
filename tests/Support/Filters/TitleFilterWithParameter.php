<?php

namespace Esign\QueryFilters\Tests\Support\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class TitleFilterWithParameter
{
    public function handle(Builder $builder, Closure $next, string $value): Builder
    {
        $builder->where('title', 'like', "%$value%");

        return $next($builder);
    }
}
