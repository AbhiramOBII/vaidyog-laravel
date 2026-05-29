<?php

namespace App\Livewire\Admin\Blogs;

use App\Models\Blog;
use App\Models\BlogCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Blogs'])]
class BlogIndex extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $status = '';
    #[Url] public string $categoryId = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }
    public function updatedCategoryId(): void { $this->resetPage(); }

    public function toggleStatus(int $id): void
    {
        $blog = Blog::findOrFail($id);
        $newStatus = $blog->status === 'published' ? 'draft' : 'published';
        $blog->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? now() : null,
        ]);
    }

    public function delete(int $id): void
    {
        Blog::findOrFail($id)->delete();
    }

    public function render()
    {
        $query = Blog::with('category');

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        return view('livewire.admin.blogs.blog-index', [
            'blogs' => $query->latest()->paginate(20),
            'categories' => BlogCategory::active()->pluck('title', 'id'),
        ]);
    }
}
