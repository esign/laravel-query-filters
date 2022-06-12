# Apply filters to Laravel's query builder.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/esign/laravel-query-filters.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-query-filters)
[![Total Downloads](https://img.shields.io/packagist/dt/esign/laravel-query-filters.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-query-filters)
![GitHub Actions](https://github.com/esign/laravel-query-filters/actions/workflows/main.yml/badge.svg)

This package allows you to easily apply filters to Laravel's query builder by abstracting filter logic into dedicated classes.

## Installation

You can install the package via composer:

```bash
composer require esign/laravel-query-filters
```

The package will automatically register a service provider.

## Usage

### Preparing your model
To apply filters to your model you may use the `Esign\QueryFilters\Concerns\Filterable` trait:
```php
use Esign\QueryFilters\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Filterable;
}
```

### Applying filters
After applying the trait to your model, a query scope `filter` will be available, that accepts an array of possible filters:
```php
use App\Models\Filters\TitleFilter;
use App\Models\Filters\BodyFilter;

Post::filter([
    TitleFilter::class,
    BodyFilter::class,
])->get();
```

The `filter` scope will send the query builder through an array of filters.
To pass the query builder to the next filter, you should call the `$next` callback with the `$query`.

You're not limited to only using string based classes as filters, you can pass actual instances, callbacks, or pass parameters along with your class based string:
```php
use App\Models\Filters\TitleFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

Post::filter([
    // Class strings
    TitleFilter::class,
    // Class strings that pass a parameter to the handle method
    TitleFilter::class . ':dogs',
    // Class instance with a constructor parameter
    new TitleFilter('dogs'),
    // Use a callback
    function (Builder $query, Closure $next): Builder {
        $query->where('title', 'like', '%dogs%');

        return $next($query);
    },
])->get();
```

### Defining default filters
In case you do not provide an array of filter items, you may define a set of default filters on your model:
```php
use App\Models\Filters\TitleFilter;
use Esign\QueryFilters\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Filterable;

    public function getFilters(): array
    {
        return [
            TitleFilter::class,
        ];
    }
}
```

You may now call the `filter` scope without passing an array:
```php
Post::filter()->get();
```

### Creating filters
To create a filter class you may use the `make:filter` Artisan command:
```bash
php artisan make:filter TitleFilter
```

```php
namespace App\Models\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TitleFilter
{
    public function __construct(protected Request $request)
    {}

    public function handle(Builder $query, Closure $next): Builder
    {
        $query->where('title', 'like', '%' . $this->request->query('search') . '%');

        return $next($query);
    }
}
```

### Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
