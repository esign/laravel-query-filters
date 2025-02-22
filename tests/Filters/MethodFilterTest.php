<?php

namespace Esign\QueryFilters\Tests\Filters;

use PHPUnit\Framework\Attributes\Test;
use Esign\QueryFilters\Tests\Support\Filters\PostFilter;
use Esign\QueryFilters\Tests\Support\Models\Post;
use Esign\QueryFilters\Tests\TestCase;
use Illuminate\Http\Request;

class MethodFilterTest extends TestCase
{
    #[Test]
    public function it_can_apply_a_filter_if_a_valid_value_is_given()
    {
        $request = new Request(['title' => 'dogs']);
        $postA = Post::create(['title' => 'Post about dogs']);
        $postB = Post::create(['title' => 'Post about cats']);

        $filteredPosts = Post::filter([
            new PostFilter($request),
        ])->get();

        $this->assertTrue($filteredPosts->contains($postA));
        $this->assertFalse($filteredPosts->contains($postB));
    }

    #[Test]
    public function it_wont_apply_a_filter_if_an_invalid_value_is_given()
    {
        $request = new Request(['title' => null]);
        $postA = Post::create(['title' => 'Post about dogs']);
        $postB = Post::create(['title' => 'Post about cats']);

        $filteredPosts = Post::filter([
            new PostFilter($request),
        ])->get();

        $this->assertTrue($filteredPosts->contains($postA));
        $this->assertTrue($filteredPosts->contains($postB));
    }

    #[Test]
    public function it_wont_apply_a_filter_if_no_value_is_given()
    {
        $request = new Request();
        $postA = Post::create(['title' => 'Post about dogs']);
        $postB = Post::create(['title' => 'Post about cats']);

        $filteredPosts = Post::filter([
            new PostFilter($request),
        ])->get();

        $this->assertTrue($filteredPosts->contains($postA));
        $this->assertTrue($filteredPosts->contains($postB));
    }

    #[Test]
    public function it_wont_apply_a_filter_if_no_method_exists()
    {
        $request = new Request(['non_existing_query_method' => 'abc']);
        $postA = Post::create(['title' => 'Post about dogs']);
        $postB = Post::create(['title' => 'Post about cats']);

        $filteredPosts = Post::filter([
            new PostFilter($request),
        ])->get();

        $this->assertTrue($filteredPosts->contains($postA));
        $this->assertTrue($filteredPosts->contains($postB));
    }

    #[Test]
    public function it_can_guess_the_camelcased_method_name()
    {
        $request = new Request(['publish_date' => '2022-01-01']);
        $postA = Post::create(['publish_date' => '2022-01-01']);
        $postB = Post::create(['publish_date' => '2022-12-31']);

        $filteredPosts = Post::filter([
            new PostFilter($request),
        ])->get();

        $this->assertTrue($filteredPosts->contains($postA));
        $this->assertFalse($filteredPosts->contains($postB));
    }
}
