<?php

namespace Esign\QueryFilters\Tests\Support\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class TitleFilter
{
    public function handle(Builder $builder, Closure $next): Builder
    {
        $builder->where('title', 'like', '%dog%');

        return $next($builder);
    }
}
