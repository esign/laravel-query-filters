<?php

namespace Esign\QueryFilters\Tests\Support\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class TitleFilterWithConstructor
{
    public function __construct(protected string $value)
    {
    }

    public function handle(Builder $builder, Closure $next): Builder
    {
        $builder->where('title', 'like', "%$this->value%");

        return $next($builder);
    }
}