<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Faq;
use Database\Seeders\BlogSeeder;
use Database\Seeders\FaqSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_faq_seeder_creates_faq_record(): void
    {
        $this->seed(FaqSeeder::class);

        $faq = Faq::first();

        $this->assertNotNull($faq);
        $this->assertIsArray($faq->items);
        $this->assertGreaterThan(0, count($faq->items));
    }

    public function test_faq_seeder_items_structure(): void
    {
        $this->seed(FaqSeeder::class);

        $faq = Faq::first();

        foreach ($faq->items as $item) {
            $this->assertArrayHasKey('question', $item);
            $this->assertArrayHasKey('answer', $item);
        }
    }

    public function test_blog_seeder_creates_categories(): void
    {
        $this->seed(BlogSeeder::class);

        $categories = BlogCategory::all();

        $this->assertGreaterThanOrEqual(4, $categories->count());
        $this->assertTrue($categories->contains('title', 'Career Advice'));
        $this->assertTrue($categories->contains('title', 'Industry News'));
        $this->assertTrue($categories->contains('title', 'Interview Tips'));
        $this->assertTrue($categories->contains('title', 'Health & Wellness'));
    }

    public function test_blog_seeder_creates_blogs(): void
    {
        $this->seed(BlogSeeder::class);

        $blogs = Blog::all();

        $this->assertGreaterThanOrEqual(9, $blogs->count());
    }

    public function test_blog_seeder_blogs_are_published(): void
    {
        $this->seed(BlogSeeder::class);

        $blogs = Blog::all();

        foreach ($blogs as $blog) {
            $this->assertEquals('published', $blog->status);
            $this->assertNotNull($blog->published_at);
        }
    }

    public function test_blog_seeder_blogs_have_slugs(): void
    {
        $this->seed(BlogSeeder::class);

        $blogs = Blog::all();

        foreach ($blogs as $blog) {
            $this->assertNotEmpty($blog->slug);
            $this->assertStringNotContainsString(' ', $blog->slug);
        }
    }

    public function test_blog_seeder_is_idempotent(): void
    {
        $this->seed(BlogSeeder::class);
        $count1 = Blog::count();

        $this->seed(BlogSeeder::class);
        $count2 = Blog::count();

        $this->assertEquals($count1, $count2);
    }

    public function test_faq_seeder_is_idempotent(): void
    {
        $this->seed(FaqSeeder::class);
        $count1 = Faq::count();

        $this->seed(FaqSeeder::class);
        $count2 = Faq::count();

        $this->assertEquals($count1, $count2);
    }
}
