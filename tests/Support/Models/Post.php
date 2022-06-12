<?php

namespace Esign\QueryFilters\Tests\Support\Models;

use Esign\QueryFilters\Concerns\Filterable;
use Esign\QueryFilters\Tests\Support\Filters\TitleFilter;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Filterable;

    protected $guarded = [];
    protected $table = 'posts';
    public $timestamps = false;

    public function getFilters(): array
    {
        return [TitleFilter::class];
    }
}