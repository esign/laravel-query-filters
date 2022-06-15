<?php

namespace Esign\QueryFilters\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class MethodFilter
{
    protected Builder $query;

    public function __construct(
        protected Request $request
    ) {
    }

    public function handle(Builder $query, Closure $next): Builder
    {
        $this->query = $query;

        foreach ($this->getFilters() as $name => $value) {
            if ($this->shouldCallMethod($name, $value)) {
                $this->{$this->guessMethodName($name)}($value);
            }
        }

        return $next($this->query);
    }

    protected function getFilters(): array
    {
        return $this->request->query();
    }

    protected function shouldCallMethod(string $name, mixed $value): bool
    {
        if (empty($value)) {
            return false;
        }

        if (! method_exists($this, $this->guessMethodName($name))) {
            return false;
        }

        return true;
    }

    protected function guessMethodName(string $name): string
    {
        return Str::camel($name);
    }
}
