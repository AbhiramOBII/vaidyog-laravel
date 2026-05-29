<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Faq;
use App\Models\JobPosting;
use App\Models\MedicalInstitution;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Vaidyog');
    }

    public function test_homepage_displays_hero_section(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee("India's #1 Healthcare Job Portal", false);
        $response->assertSee('Find Your Next Healthcare Role');
    }

    public function test_homepage_displays_featured_jobs_section(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Featured Jobs');
    }

    public function test_homepage_displays_latest_blogs_when_available(): void
    {
        $category = BlogCategory::create(['title' => 'Test', 'status' => 'active']);
        Blog::create([
            'title' => 'Test Blog on Homepage',
            'category_id' => $category->id,
            'short_description' => 'Short desc',
            'full_description' => '<p>Content</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Latest from Our Blog');
        $response->assertSee('Test Blog on Homepage');
    }

    public function test_homepage_hides_blog_section_when_no_blogs(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Latest from Our Blog');
    }

    public function test_homepage_displays_mobile_app_section(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Your Healthcare Job Search,');
        $response->assertSee('Anytime, Anywhere.');
    }

    public function test_homepage_displays_trust_strip(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('500+ Hospitals Onboarded');
    }

    public function test_homepage_displays_recruiter_section(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('For Recruiters', false);
    }
}
