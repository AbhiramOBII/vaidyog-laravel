<?php

namespace Tests\Unit;

use App\Enums\ApplicationRankingEnum;
use App\Enums\ApplicationStatusEnum;
use App\Exceptions\InvalidStatusTransitionException;
use App\Services\Application\StatusTransitionService;
use PHPUnit\Framework\TestCase;

class ApplicationStatusTransitionTest extends TestCase
{
    private StatusTransitionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StatusTransitionService();
    }

    public function test_applied_allows_reviewed_shortlisted_rejected(): void
    {
        $allowed = $this->service->getAllowedNextStatuses('applied');
        $this->assertEquals(['reviewed', 'shortlisted', 'rejected'], $allowed);
    }

    public function test_reviewed_allows_shortlisted_interviewed_rejected(): void
    {
        $allowed = $this->service->getAllowedNextStatuses('reviewed');
        $this->assertEquals(['shortlisted', 'interviewed', 'rejected'], $allowed);
    }

    public function test_shortlisted_allows_interviewed_rejected(): void
    {
        $allowed = $this->service->getAllowedNextStatuses('shortlisted');
        $this->assertEquals(['interviewed', 'rejected'], $allowed);
    }

    public function test_interviewed_allows_offered_rejected_scheduled(): void
    {
        $allowed = $this->service->getAllowedNextStatuses('interviewed');
        $this->assertEquals(['offered', 'rejected', 'scheduled'], $allowed);
    }

    public function test_scheduled_allows_interviewed_offered_rejected(): void
    {
        $allowed = $this->service->getAllowedNextStatuses('scheduled');
        $this->assertEquals(['interviewed', 'offered', 'rejected'], $allowed);
    }

    public function test_offered_is_terminal(): void
    {
        $allowed = $this->service->getAllowedNextStatuses('offered');
        $this->assertEmpty($allowed);
    }

    public function test_rejected_is_terminal(): void
    {
        $allowed = $this->service->getAllowedNextStatuses('rejected');
        $this->assertEmpty($allowed);
    }

    public function test_pending_allows_reviewed_shortlisted_rejected(): void
    {
        $allowed = $this->service->getAllowedNextStatuses('pending');
        $this->assertEquals(['reviewed', 'shortlisted', 'rejected'], $allowed);
    }

    public function test_invalid_transition_throws_exception(): void
    {
        $this->expectException(InvalidStatusTransitionException::class);
        $this->service->validateTransition('reviewed', 'applied');
    }

    public function test_valid_transition_does_not_throw(): void
    {
        $this->service->validateTransition('applied', 'reviewed');
        $this->assertTrue(true);
    }

    public function test_offered_status_is_terminal_via_enum(): void
    {
        $this->assertTrue(ApplicationStatusEnum::Offered->isTerminal());
    }

    public function test_rejected_status_is_terminal_via_enum(): void
    {
        $this->assertTrue(ApplicationStatusEnum::Rejected->isTerminal());
    }

    public function test_applied_status_is_not_terminal(): void
    {
        $this->assertFalse(ApplicationStatusEnum::Applied->isTerminal());
    }

    public function test_ranking_get_color_returns_correct_values(): void
    {
        $this->assertEquals('amber', ApplicationRankingEnum::A->getColor());
        $this->assertEquals('gray', ApplicationRankingEnum::B->getColor());
        $this->assertEquals('orange', ApplicationRankingEnum::C->getColor());
        $this->assertEquals('neutral', ApplicationRankingEnum::D->getColor());
    }

    public function test_ranking_get_label_returns_correct_values(): void
    {
        $this->assertEquals('Platinum Applicant', ApplicationRankingEnum::A->getLabel());
        $this->assertEquals('Gold Applicant', ApplicationRankingEnum::B->getLabel());
        $this->assertEquals('Silver Applicant', ApplicationRankingEnum::C->getLabel());
        $this->assertEquals('Basic Applicant', ApplicationRankingEnum::D->getLabel());
    }
}
