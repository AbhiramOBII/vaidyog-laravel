<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin', ['pageTitle' => 'News Article'])]
class NewsForm extends Component
{
    use WithFileUploads;

    public ?int $newsId = null;

    public string $title = '';
    public string $slug = '';
    public ?int $category_id = null;
    public string $short_description = '';
    public string $full_description = '';
    public string $status = 'draft';
    public $thumbnail_image = null;
    public ?string $existing_thumbnail = null;
    public string $meta_title = '';
    public string $meta_description = '';

    public function mount(?int $news = null): void
    {
        if ($news) {
            $article = News::findOrFail($news);
            $this->newsId = $article->id;
            $this->title = $article->title;
            $this->slug = $article->slug;
            $this->category_id = $article->category_id;
            $this->short_description = $article->short_description ?? '';
            $this->full_description = $article->full_description ?? '';
            $this->status = $article->status;
            $this->existing_thumbnail = $article->thumbnail_image;
            $this->meta_title = $article->meta_title ?? '';
            $this->meta_description = $article->meta_description ?? '';
        }
    }

    public function updatedTitle(): void
    {
        if (!$this->newsId) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save(): void
    {
        $rules = [
            'title' => 'required|max:200',
            'slug' => 'required|max:200|unique:news,slug' . ($this->newsId ? ",{$this->newsId}" : ''),
            'category_id' => 'required|exists:news_categories,id',
            'short_description' => 'nullable|max:500',
            'full_description' => 'nullable',
            'status' => 'required|in:draft,published',
            'thumbnail_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|max:200',
            'meta_description' => 'nullable|max:500',
        ];

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'short_description' => $this->short_description ?: null,
            'full_description' => $this->full_description ?: null,
            'status' => $this->status,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
        ];

        if ($this->thumbnail_image) {
            $data['thumbnail_image'] = $this->thumbnail_image->store('news', 'public');
        }

        if ($this->status === 'published') {
            $data['published_at'] = now();
        }

        if ($this->newsId) {
            News::findOrFail($this->newsId)->update($data);
            session()->flash('success', 'News article updated successfully.');
        } else {
            News::create($data);
            session()->flash('success', 'News article created successfully.');
        }

        $this->redirect(route('admin.news.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.news.news-form', [
            'categories' => NewsCategory::active()->pluck('title', 'id'),
        ]);
    }
}
