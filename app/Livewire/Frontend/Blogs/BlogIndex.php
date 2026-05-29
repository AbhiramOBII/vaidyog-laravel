<?php

namespace App\Livewire\Frontend\Blogs;

use App\Models\Blog;
use App\Models\BlogCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class BlogIndex extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $category = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Blog::published()->with('category')->latest('published_at');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('short_description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->category));
        }

        return view('livewire.frontend.blogs.blog-index', [
            'blogs' => $query->paginate(9),
            'categories' => BlogCategory::active()->parents()->whereHas('blogs', fn ($q) => $q->published())->get(),
        ]);
    }
}
