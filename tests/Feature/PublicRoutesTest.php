<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_is_accessible(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_jobs_index_is_accessible(): void
    {
        $this->get('/best-healthcare-jobs')->assertStatus(200);
    }

    public function test_blogs_index_is_accessible(): void
    {
        $this->get('/blogs')->assertStatus(200);
    }

    public function test_plans_page_is_accessible(): void
    {
        $this->get('/plans')->assertStatus(200);
    }

    public function test_terms_page_is_accessible(): void
    {
        $this->get('/terms')->assertStatus(200);
    }

    public function test_privacy_page_is_accessible(): void
    {
        $this->get('/privacy')->assertStatus(200);
    }

    public function test_disclaimer_page_is_accessible(): void
    {
        $this->get('/disclaimer')->assertStatus(200);
    }

    public function test_invalid_blog_slug_returns_404(): void
    {
        $this->get('/blogs/non-existent-slug')->assertStatus(404);
    }
}
