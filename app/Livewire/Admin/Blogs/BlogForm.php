<?php

namespace App\Livewire\Admin\Blogs;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin', ['pageTitle' => 'Blog Post'])]
class BlogForm extends Component
{
    use WithFileUploads;

    public ?int $blogId = null;

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

    public function mount(?int $blog = null): void
    {
        if ($blog) {
            $post = Blog::findOrFail($blog);
            $this->blogId = $post->id;
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->category_id = $post->category_id;
            $this->short_description = $post->short_description ?? '';
            $this->full_description = $post->full_description ?? '';
            $this->status = $post->status;
            $this->existing_thumbnail = $post->thumbnail_image;
            $this->meta_title = $post->meta_title ?? '';
            $this->meta_description = $post->meta_description ?? '';
        }
    }

    public function updatedTitle(): void
    {
        if (!$this->blogId) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save(): void
    {
        $rules = [
            'title' => 'required|max:200',
            'slug' => 'required|max:200|unique:blogs,slug' . ($this->blogId ? ",{$this->blogId}" : ''),
            'category_id' => 'required|exists:blog_categories,id',
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
            $data['thumbnail_image'] = $this->thumbnail_image->store('blogs', 'public');
        }

        if ($this->status === 'published') {
            $data['published_at'] = now();
        }

        if ($this->blogId) {
            Blog::findOrFail($this->blogId)->update($data);
            session()->flash('success', 'Blog post updated successfully.');
        } else {
            Blog::create($data);
            session()->flash('success', 'Blog post created successfully.');
        }

        $this->redirect(route('admin.blogs.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.blogs.blog-form', [
            'categories' => BlogCategory::active()->pluck('title', 'id'),
        ]);
    }
}
