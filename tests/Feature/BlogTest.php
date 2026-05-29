<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    private static int $categoryCount = 0;
    private static int $blogCount = 0;

    private function createCategory(array $overrides = []): BlogCategory
    {
        self::$categoryCount++;
        return BlogCategory::create(array_merge([
            'title' => 'Test Category ' . self::$categoryCount . '_' . uniqid(),
            'status' => 'active',
        ], $overrides));
    }

    private function createBlog(array $overrides = []): Blog
    {
        self::$blogCount++;
        if (!isset($overrides['category_id'])) {
            $overrides['category_id'] = $this->createCategory()->id;
        }

        return Blog::create(array_merge([
            'title' => 'Test Blog Post ' . self::$blogCount . '_' . uniqid(),
            'short_description' => 'A short description for testing.',
            'full_description' => '<p>Full content of the blog post.</p>',
            'status' => 'published',
            'published_at' => now(),
        ], $overrides));
    }

    // ─── Model Tests ──────────────────────────────────────────────────

    public function test_blog_generates_slug_on_creation(): void
    {
        $blog = $this->createBlog(['title' => 'My Healthcare Blog Post']);

        $this->assertEquals('my-healthcare-blog-post', $blog->slug);
    }

    public function test_blog_belongs_to_category(): void
    {
        $category = $this->createCategory(['title' => 'Career Advice']);
        $blog = $this->createBlog(['category_id' => $category->id]);

        $this->assertInstanceOf(BlogCategory::class, $blog->category);
        $this->assertEquals('Career Advice', $blog->category->title);
    }

    public function test_blog_category_has_many_blogs(): void
    {
        $category = $this->createCategory();
        $this->createBlog(['title' => 'Blog 1', 'category_id' => $category->id]);
        $this->createBlog(['title' => 'Blog 2', 'category_id' => $category->id]);

        $this->assertCount(2, $category->blogs);
    }

    public function test_blog_published_scope_only_returns_published(): void
    {
        $this->createBlog(['title' => 'Published Post', 'status' => 'published']);
        $this->createBlog(['title' => 'Draft Post', 'status' => 'draft']);

        $published = Blog::published()->get();

        $this->assertCount(1, $published);
        $this->assertEquals('Published Post', $published->first()->title);
    }

    public function test_blog_draft_scope_only_returns_drafts(): void
    {
        $this->createBlog(['title' => 'Published Post', 'status' => 'published']);
        $this->createBlog(['title' => 'Draft Post', 'status' => 'draft']);

        $drafts = Blog::draft()->get();

        $this->assertCount(1, $drafts);
        $this->assertEquals('Draft Post', $drafts->first()->title);
    }

    public function test_blog_category_active_scope(): void
    {
        $this->createCategory(['title' => 'Active Cat', 'status' => 'active']);
        $this->createCategory(['title' => 'Inactive Cat', 'status' => 'inactive']);

        $active = BlogCategory::active()->get();

        $this->assertCount(1, $active);
        $this->assertEquals('Active Cat', $active->first()->title);
    }

    public function test_blog_category_generates_slug(): void
    {
        $category = $this->createCategory(['title' => 'Interview Tips']);

        $this->assertEquals('interview-tips', $category->slug);
    }

    // ─── Route / Page Tests ───────────────────────────────────────────

    public function test_blog_index_page_loads(): void
    {
        $this->createBlog(['title' => 'Visible Blog']);

        $response = $this->get(route('blogs.index'));

        $response->assertStatus(200);
        $response->assertSee('Visible Blog');
    }

    public function test_blog_index_does_not_show_drafts(): void
    {
        $this->createBlog(['title' => 'Draft Post', 'status' => 'draft']);

        $response = $this->get(route('blogs.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Draft Post');
    }

    public function test_blog_show_page_loads(): void
    {
        $blog = $this->createBlog([
            'title' => 'Detailed Healthcare Article',
            'full_description' => '<p>Full article content here.</p>',
        ]);

        $response = $this->get(route('blogs.show', $blog->slug));

        $response->assertStatus(200);
        $response->assertSee('Detailed Healthcare Article');
        $response->assertSee('Full article content here.');
    }

    public function test_blog_show_returns_404_for_draft(): void
    {
        $blog = $this->createBlog(['title' => 'Secret Draft', 'status' => 'draft']);

        $response = $this->get(route('blogs.show', $blog->slug));

        $response->assertStatus(404);
    }

    public function test_blog_show_returns_404_for_invalid_slug(): void
    {
        $response = $this->get(route('blogs.show', 'non-existent-post'));

        $response->assertStatus(404);
    }

    public function test_blog_index_filters_by_category(): void
    {
        $cat1 = $this->createCategory(['title' => 'Career']);
        $cat2 = $this->createCategory(['title' => 'News']);
        $this->createBlog(['title' => 'Career Post', 'category_id' => $cat1->id]);
        $this->createBlog(['title' => 'News Post', 'category_id' => $cat2->id]);

        $response = $this->get(route('blogs.index', ['category' => 'career']));

        $response->assertStatus(200);
        $response->assertSee('Career Post');
        $response->assertDontSee('News Post');
    }

    public function test_blog_index_search_filters_results(): void
    {
        $this->createBlog(['title' => 'Nursing Career Tips']);
        $this->createBlog(['title' => 'Pharmacy Hiring Trends']);

        $response = $this->get(route('blogs.index', ['search' => 'Nursing']));

        $response->assertStatus(200);
        $response->assertSee('Nursing Career Tips');
        $response->assertDontSee('Pharmacy Hiring Trends');
    }
}
