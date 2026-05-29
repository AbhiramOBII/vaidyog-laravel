<?php

namespace App\Livewire\Admin\Blogs;

use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin', ['pageTitle' => 'Blog Category'])]
class BlogCategoryForm extends Component
{
    use WithFileUploads;

    public ?int $categoryId = null;

    public string $title = '';
    public string $slug = '';
    public ?int $parent_id = null;
    public string $short_description = '';
    public string $full_description = '';
    public string $status = 'active';
    public $thumbnail_image = null;
    public ?string $existing_thumbnail = null;
    public string $meta_title = '';
    public string $meta_description = '';

    public function mount(?int $category = null): void
    {
        if ($category) {
            $cat = BlogCategory::findOrFail($category);
            $this->categoryId = $cat->id;
            $this->title = $cat->title;
            $this->slug = $cat->slug;
            $this->parent_id = $cat->parent_id;
            $this->short_description = $cat->short_description ?? '';
            $this->full_description = $cat->full_description ?? '';
            $this->status = $cat->status;
            $this->existing_thumbnail = $cat->thumbnail_image;
            $this->meta_title = $cat->meta_title ?? '';
            $this->meta_description = $cat->meta_description ?? '';
        }
    }

    public function updatedTitle(): void
    {
        if (!$this->categoryId) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save(): void
    {
        $rules = [
            'title' => 'required|max:200',
            'slug' => 'required|max:200|unique:blog_categories,slug' . ($this->categoryId ? ",{$this->categoryId}" : ''),
            'parent_id' => 'nullable|exists:blog_categories,id',
            'short_description' => 'nullable|max:500',
            'full_description' => 'nullable',
            'status' => 'required|in:active,inactive',
            'thumbnail_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|max:200',
            'meta_description' => 'nullable|max:500',
        ];

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
            'short_description' => $this->short_description ?: null,
            'full_description' => $this->full_description ?: null,
            'status' => $this->status,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
        ];

        if ($this->thumbnail_image) {
            $data['thumbnail_image'] = $this->thumbnail_image->store('blog-categories', 'public');
        }

        if ($this->categoryId) {
            BlogCategory::findOrFail($this->categoryId)->update($data);
            session()->flash('success', 'Category updated successfully.');
        } else {
            BlogCategory::create($data);
            session()->flash('success', 'Category created successfully.');
        }

        $this->redirect(route('admin.blog-categories.index'), navigate: true);
    }

    public function render()
    {
        $parentCategories = BlogCategory::parents()
            ->when($this->categoryId, fn($q) => $q->where('id', '!=', $this->categoryId))
            ->active()
            ->pluck('title', 'id');

        return view('livewire.admin.blogs.blog-category-form', [
            'parentCategories' => $parentCategories,
        ]);
    }
}
