<?php

namespace Tests\Feature;

use App\Livewire\Admin\Support\TicketIndex;
use App\Livewire\Admin\Support\TicketShow;
use App\Models\Admin;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminSupportTicketTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private User $user;
    private SupportTicket $ticket;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => 'password',
            'is_active' => true,
        ]);
        $this->user = User::factory()->create();
        $this->ticket = SupportTicket::create([
            'user_id' => $this->user->id,
            'title' => 'Test Ticket',
            'description' => 'I need help with something.',
            'status' => 'raised',
            'status_dates' => ['raised' => now()->toDateTimeString()],
            'comments' => [],
        ]);
    }

    public function test_admin_can_view_ticket_list(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.support-tickets.index'))
            ->assertOk()
            ->assertSee('Test Ticket');
    }

    public function test_admin_can_view_ticket_detail(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.support-tickets.show', $this->ticket->id))
            ->assertOk()
            ->assertSee('I need help with something.');
    }

    public function test_admin_can_update_ticket_status(): void
    {
        Livewire::actingAs($this->admin, 'admin')
            ->test(TicketShow::class, ['ticketId' => $this->ticket->id])
            ->set('newStatus', 'in-progress')
            ->call('updateStatus');

        $this->ticket->refresh();
        $this->assertEquals('in-progress', $this->ticket->status);
        $this->assertArrayHasKey('in-progress', $this->ticket->status_dates);
    }

    public function test_admin_can_add_comment(): void
    {
        Livewire::actingAs($this->admin, 'admin')
            ->test(TicketShow::class, ['ticketId' => $this->ticket->id])
            ->set('newComment', 'We are investigating this issue.')
            ->call('addComment')
            ->assertSet('newComment', '');

        $this->ticket->refresh();
        $this->assertCount(1, $this->ticket->comments);
        $this->assertEquals('We are investigating this issue.', $this->ticket->comments[0]['message']);
        $this->assertEquals('admin', $this->ticket->comments[0]['role']);
    }

    public function test_admin_ticket_list_filters_by_status(): void
    {
        SupportTicket::create([
            'user_id' => $this->user->id,
            'title' => 'Resolved Ticket',
            'description' => 'Already fixed.',
            'status' => 'resolved',
            'status_dates' => ['raised' => now()->toDateTimeString(), 'resolved' => now()->toDateTimeString()],
            'comments' => [],
        ]);

        Livewire::actingAs($this->admin, 'admin')
            ->test(TicketIndex::class)
            ->set('status', 'resolved')
            ->assertSee('Resolved Ticket')
            ->assertDontSee('Test Ticket');
    }

    public function test_admin_ticket_list_search_works(): void
    {
        Livewire::actingAs($this->admin, 'admin')
            ->test(TicketIndex::class)
            ->set('search', 'Test Ticket')
            ->assertSee('Test Ticket');
    }
}
