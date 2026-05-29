<?php

namespace App\Livewire\Admin\Feedback;

use App\Models\Feedback;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Feedbacks'])]
class FeedbackIndex extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $userType = '';
    #[Url] public string $readStatus = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedUserType(): void { $this->resetPage(); }
    public function updatedReadStatus(): void { $this->resetPage(); }

    public function markAsRead(int $id): void
    {
        Feedback::findOrFail($id)->update(['read_status' => true]);
    }

    public function markAsUnread(int $id): void
    {
        Feedback::findOrFail($id)->update(['read_status' => false]);
    }

    public function delete(int $id): void
    {
        Feedback::findOrFail($id)->delete();
    }

    public function render()
    {
        $query = Feedback::with('user');

        if ($this->search) {
            $query->where(fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('feedback', 'like', "%{$this->search}%"));
        }
        if ($this->userType) {
            $query->where('user_type', $this->userType);
        }
        if ($this->readStatus !== '') {
            $query->where('read_status', $this->readStatus === 'read');
        }

        return view('livewire.admin.feedback.feedback-index', [
            'feedbacks' => $query->latest()->paginate(20),
        ]);
    }
}
