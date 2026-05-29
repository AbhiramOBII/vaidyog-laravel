<?php

namespace App\Livewire\Admin\Support;

use App\Models\SupportTicket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Support Tickets'])]
class TicketIndex extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $status = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }

    public function render()
    {
        $query = SupportTicket::with('user');

        if ($this->search) {
            $query->where(fn($q) => $q->where('title', 'like', "%{$this->search}%")
                ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")));
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }

        return view('livewire.admin.support.ticket-index', [
            'tickets' => $query->latest()->paginate(20),
        ]);
    }
}
