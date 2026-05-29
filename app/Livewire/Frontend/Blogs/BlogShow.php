<?php

namespace App\Livewire\Frontend\Blogs;

use App\Models\Blog;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class BlogShow extends Component
{
    public Blog $blog;

    public function mount(string $slug): void
    {
        $this->blog = Blog::published()->where('slug', $slug)->firstOrFail();
        $this->blog->load('category');
    }

    public function render()
    {
        $relatedBlogs = Blog::published()
            ->where('id', '!=', $this->blog->id)
            ->where('category_id', $this->blog->category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('livewire.frontend.blogs.blog-show', [
            'relatedBlogs' => $relatedBlogs,
        ]);
    }
}
