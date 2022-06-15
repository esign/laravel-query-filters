<?php

namespace Esign\QueryFilters\Tests\Support\Filters;

use Esign\QueryFilters\Filters\MethodFilter;
use Illuminate\Database\Eloquent\Builder;

class PostFilter extends MethodFilter
{
    public function title(mixed $value): Builder
    {
        return $this->query->where('title', 'like', "%$value%");
    }

    public function publishDate(mixed $value): Builder
    {
        return $this->query->where('publish_date', '=', $value);
    }
}
