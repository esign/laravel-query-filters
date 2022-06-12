<?php

namespace Esign\QueryFilters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

trait Filterable
{
    public function scopeFilter(Builder $query, ?array $filters = null): Builder
    {
        app(Pipeline::class)
            ->send($query)
            ->through($filters ?? $this->getFilters())
            ->thenReturn();

        return $query;
    }

    public function getFilters(): array
    {
        return [];
    }
}
