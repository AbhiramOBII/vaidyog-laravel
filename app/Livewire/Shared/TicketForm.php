<?php

namespace App\Livewire\Shared;

use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketForm extends Component
{
    public string $title = '';
    public string $description = '';

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

    public function render()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())->latest()->limit(10)->get();

        return view('livewire.shared.ticket-form', [
            'tickets' => $tickets,
        ]);
    }
}
