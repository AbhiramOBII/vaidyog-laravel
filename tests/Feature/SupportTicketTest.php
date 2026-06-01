<?php

namespace Tests\Feature;

use App\Livewire\Shared\TicketForm;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SupportTicketTest extends TestCase
{
    use RefreshDatabase;

    private User $jobSeeker;
    private User $recruiter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jobSeeker = User::factory()->create(['user_type' => 'user']);
        $this->recruiter = User::factory()->create(['user_type' => 'MedicalInstitution', 'is_profile_completed' => true]);
    }

    public function test_jobseeker_can_access_support_page(): void
    {
        $this->actingAs($this->jobSeeker)
            ->get(route('jobseeker.support'))
            ->assertOk();
    }

    public function test_recruiter_can_access_support_page(): void
    {
        $this->actingAs($this->recruiter)
            ->get(route('recruiter.support'))
            ->assertOk();
    }

    public function test_unauthenticated_user_cannot_access_support(): void
    {
        $this->get('/support')->assertRedirect();
    }

    public function test_user_can_submit_ticket(): void
    {
        Livewire::actingAs($this->jobSeeker)
            ->test(TicketForm::class)
            ->set('title', 'Cannot apply to jobs')
            ->set('description', 'I am unable to submit my application for any job posting.')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $this->jobSeeker->id,
            'title' => 'Cannot apply to jobs',
            'status' => 'raised',
        ]);
    }

    public function test_ticket_requires_title_and_description(): void
    {
        Livewire::actingAs($this->jobSeeker)
            ->test(TicketForm::class)
            ->set('title', '')
            ->set('description', '')
            ->call('submit')
            ->assertHasErrors(['title', 'description']);
    }

    public function test_user_can_view_own_ticket(): void
    {
        $ticket = SupportTicket::create([
            'user_id' => $this->jobSeeker->id,
            'title' => 'My Issue',
            'description' => 'Details here',
            'status' => 'raised',
            'status_dates' => ['raised' => now()->toDateTimeString()],
            'comments' => [],
        ]);

        Livewire::actingAs($this->jobSeeker)
            ->test(TicketForm::class)
            ->call('viewTicket', $ticket->id)
            ->assertSet('viewingTicketId', $ticket->id);
    }

    public function test_user_cannot_view_other_users_ticket(): void
    {
        $ticket = SupportTicket::create([
            'user_id' => $this->recruiter->id,
            'title' => 'Recruiter Issue',
            'description' => 'Not my ticket',
            'status' => 'raised',
            'status_dates' => ['raised' => now()->toDateTimeString()],
            'comments' => [],
        ]);

        Livewire::actingAs($this->jobSeeker)
            ->test(TicketForm::class)
            ->call('viewTicket', $ticket->id)
            ->assertSet('viewingTicketId', null);
    }

    public function test_user_can_reply_to_ticket(): void
    {
        $ticket = SupportTicket::create([
            'user_id' => $this->jobSeeker->id,
            'title' => 'My Issue',
            'description' => 'Details here',
            'status' => 'in-progress',
            'status_dates' => ['raised' => now()->toDateTimeString()],
            'comments' => [
                ['id' => 1, 'message' => 'We are looking into it.', 'author' => 'Admin', 'role' => 'admin', 'created_at' => now()->toDateTimeString()],
            ],
        ]);

        Livewire::actingAs($this->jobSeeker)
            ->test(TicketForm::class)
            ->call('viewTicket', $ticket->id)
            ->set('replyMessage', 'Thank you for the update!')
            ->call('addReply')
            ->assertHasNoErrors()
            ->assertSet('replyMessage', '');

        $ticket->refresh();
        $this->assertCount(2, $ticket->comments);
        $this->assertEquals('Thank you for the update!', $ticket->comments[1]['message']);
        $this->assertEquals('user', $ticket->comments[1]['role']);
    }

    public function test_user_cannot_reply_to_closed_ticket(): void
    {
        $ticket = SupportTicket::create([
            'user_id' => $this->jobSeeker->id,
            'title' => 'Closed Issue',
            'description' => 'Already resolved',
            'status' => 'closed',
            'status_dates' => ['raised' => now()->toDateTimeString(), 'closed' => now()->toDateTimeString()],
            'comments' => [],
        ]);

        Livewire::actingAs($this->jobSeeker)
            ->test(TicketForm::class)
            ->call('viewTicket', $ticket->id)
            ->set('replyMessage', 'Trying to reply')
            ->call('addReply');

        $ticket->refresh();
        $this->assertCount(0, $ticket->comments ?? []);
    }
}
