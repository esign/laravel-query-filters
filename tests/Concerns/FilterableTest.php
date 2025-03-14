<?php

namespace Esign\QueryFilters\Tests;

use PHPUnit\Framework\Attributes\Test;
use Closure;
use Esign\QueryFilters\Tests\Support\Filters\TitleFilterWithConstructor;
use Esign\QueryFilters\Tests\Support\Filters\TitleFilterWithParameter;
use Esign\QueryFilters\Tests\Support\Models\Post;
use Illuminate\Database\Eloquent\Builder;

final class FilterableTest extends TestCase
{
    #[Test]
    public function it_can_apply_class_filters(): void
    {
        $postA = Post::create(['title' => 'Post about dogs']);
        $postB = Post::create(['title' => 'Post about cats']);

        $filteredPosts = Post::filter()->get();

        $this->assertTrue($filteredPosts->contains($postA));
        $this->assertFalse($filteredPosts->contains($postB));
    }

    #[Test]
    public function it_can_apply_class_filters_with_paramters(): void
    {
        $postModel = new class () extends Post {
            public function getFilters(): array
            {
                return [
                    TitleFilterWithParameter::class . ':dogs',
                ];
            }
        };

        $postA = $postModel::create(['title' => 'Post about dogs']);
        $postB = $postModel::create(['title' => 'Post about cats']);

        $filteredPosts = $postModel::filter()->get();

        $this->assertTrue($filteredPosts->contains($postA));
        $this->assertFalse($filteredPosts->contains($postB));
    }

    #[Test]
    public function it_can_apply_class_filters_with_a_constructor(): void
    {
        $postModel = new class () extends Post {
            public function getFilters(): array
            {
                return [
                    new TitleFilterWithConstructor('dogs'),
                ];
            }
        };

        $postA = $postModel::create(['title' => 'Post about dogs']);
        $postB = $postModel::create(['title' => 'Post about cats']);

        $filteredPosts = $postModel::filter()->get();

        $this->assertTrue($filteredPosts->contains($postA));
        $this->assertFalse($filteredPosts->contains($postB));
    }

    #[Test]
    public function it_can_apply_class_filters_with_a_callback(): void
    {
        $postModel = new class () extends Post {
            public function getFilters(): array
            {
                return [
                    function (Builder $query, Closure $next): void {
                        $query->where('title', 'like', '%dogs%');

                        $next($query);
                    },
                ];
            }
        };

        $postA = $postModel::create(['title' => 'Post about dogs']);
        $postB = $postModel::create(['title' => 'Post about cats']);

        $filteredPosts = $postModel::filter()->get();

        $this->assertTrue($filteredPosts->contains($postA));
        $this->assertFalse($filteredPosts->contains($postB));
    }

    #[Test]
    public function it_can_pass_a_custom_filter(): void
    {
        $postA = Post::create(['title' => 'Post about dogs']);
        $postB = Post::create(['title' => 'Post about cats']);

        $filteredPosts = Post::filter([
            new TitleFilterWithConstructor('cats'),
        ])->get();

        $this->assertFalse($filteredPosts->contains($postA));
        $this->assertTrue($filteredPosts->contains($postB));
    }
}
