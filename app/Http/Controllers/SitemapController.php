<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\JobPosting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $content .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $content .= '  <sitemap><loc>' . url('/sitemap-static.xml') . '</loc></sitemap>' . "\n";
        $content .= '  <sitemap><loc>' . url('/sitemap-jobs.xml') . '</loc></sitemap>' . "\n";
        $content .= '  <sitemap><loc>' . url('/sitemap-blogs.xml') . '</loc></sitemap>' . "\n";
        $content .= '</sitemapindex>';

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function static(): Response
    {
        $urls = [
            ['loc' => url('/'), 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => route('jobs.index'), 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('plans.index'), 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => route('blogs.index'), 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => route('terms'), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => route('privacy'), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => route('disclaimer'), 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $content .= '  <url>' . "\n";
            $content .= '    <loc>' . $url['loc'] . '</loc>' . "\n";
            $content .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $content .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $content .= '  </url>' . "\n";
        }

        $content .= '</urlset>';

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function jobs(): Response
    {
        $jobs = JobPosting::publiclyVisible()
            ->select('id', 'slug', 'updated_at')
            ->latest('approved_at')
            ->get();

        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($jobs as $job) {
            $content .= '  <url>' . "\n";
            $content .= '    <loc>' . route('jobs.show', $job) . '</loc>' . "\n";
            $content .= '    <lastmod>' . $job->updated_at->toW3cString() . '</lastmod>' . "\n";
            $content .= '    <changefreq>weekly</changefreq>' . "\n";
            $content .= '    <priority>0.8</priority>' . "\n";
            $content .= '  </url>' . "\n";
        }

        $content .= '</urlset>';

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function blogs(): Response
    {
        $blogs = Blog::published()
            ->select('slug', 'updated_at')
            ->latest('published_at')
            ->get();

        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($blogs as $blog) {
            $content .= '  <url>' . "\n";
            $content .= '    <loc>' . route('blogs.show', $blog->slug) . '</loc>' . "\n";
            $content .= '    <lastmod>' . $blog->updated_at->toW3cString() . '</lastmod>' . "\n";
            $content .= '    <changefreq>monthly</changefreq>' . "\n";
            $content .= '    <priority>0.6</priority>' . "\n";
            $content .= '  </url>' . "\n";
        }

        $content .= '</urlset>';

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}
