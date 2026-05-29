<?php

namespace App\Livewire\Admin\Support;

use App\Models\SupportTicket;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Ticket Details'])]
class TicketShow extends Component
{
    public SupportTicket $ticket;
    public string $newComment = '';
    public string $newStatus = '';

    public function mount(int $ticket): void
    {
        $this->ticket = SupportTicket::with('user')->findOrFail($ticket);
        $this->newStatus = $this->ticket->status;
    }

    public function updateStatus(): void
    {
        $this->validate(['newStatus' => 'required|in:raised,in-progress,resolved,closed']);

        if ($this->newStatus !== $this->ticket->status) {
            $this->ticket->updateStatus($this->newStatus);
            $this->ticket->refresh();
        }
    }

    public function addComment(): void
    {
        $this->validate(['newComment' => 'required|string|max:2000']);

        $this->ticket->addComment($this->newComment, 'Admin', 'admin');
        $this->newComment = '';
        $this->ticket->refresh();
    }

    public function render()
    {
        return view('livewire.admin.support.ticket-show');
    }
}
