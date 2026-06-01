<?php

namespace App\Livewire\Shared;

use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketForm extends Component
{
    public string $title = '';
    public string $description = '';
    public ?int $viewingTicketId = null;
    public string $replyMessage = '';

    public function submit(): void
    {
        $this->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:5000',
        ]);

        SupportTicket::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'status' => 'raised',
            'status_dates' => ['raised' => now()->toDateTimeString()],
            'comments' => [],
        ]);

        $this->title = '';
        $this->description = '';
        session()->flash('success', 'Your support ticket has been submitted. We will get back to you soon.');
    }

    public function viewTicket(int $id): void
    {
        $ticket = SupportTicket::where('user_id', Auth::id())->find($id);
        $this->viewingTicketId = $ticket ? $ticket->id : null;
    }

    public function closeTicketView(): void
    {
        $this->viewingTicketId = null;
        $this->replyMessage = '';
    }

    public function addReply(): void
    {
        $this->validate(['replyMessage' => 'required|string|max:2000']);

        $ticket = SupportTicket::where('user_id', Auth::id())->find($this->viewingTicketId);
        if ($ticket && !in_array($ticket->status, ['closed', 'resolved'])) {
            $user = Auth::user();
            $ticket->addComment($this->replyMessage, $user->name, 'user');
            $this->replyMessage = '';
        }
    }

    public function render()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())->latest()->limit(10)->get();
        $viewingTicket = $this->viewingTicketId
            ? SupportTicket::where('user_id', Auth::id())->find($this->viewingTicketId)
            : null;

        $layout = request()->is('recruiter/*')
            ? 'components.layouts.recruiter'
            : 'components.layouts.app';

        return view('livewire.shared.ticket-form', [
            'tickets' => $tickets,
            'viewingTicket' => $viewingTicket,
        ])->layout($layout, ['pageTitle' => 'Support']);
    }
}
