<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use App\Models\NewsCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'News'])]
class NewsIndex extends Component
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
        $news = News::findOrFail($id);
        $newStatus = $news->status === 'published' ? 'draft' : 'published';
        $news->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? now() : null,
        ]);
    }

    public function delete(int $id): void
    {
        News::findOrFail($id)->delete();
    }

    public function render()
    {
        $query = News::with('category');

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        return view('livewire.admin.news.news-index', [
            'articles' => $query->latest()->paginate(20),
            'categories' => NewsCategory::active()->pluck('title', 'id'),
        ]);
    }
}
