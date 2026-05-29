<?php

namespace Tests\Feature;

use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    private function seedFaq(): Faq
    {
        return Faq::create([
            'items' => [
                ['question' => 'What is Vaidyog?', 'answer' => 'India\'s healthcare job portal.'],
                ['question' => 'Is registration free?', 'answer' => 'Yes, registration is free.'],
                ['question' => 'How do I apply?', 'answer' => 'Create a profile and click Apply Now.'],
            ],
        ]);
    }

    // ─── Model Tests ──────────────────────────────────────────────────

    public function test_faq_stores_items_as_array(): void
    {
        $faq = $this->seedFaq();

        $this->assertIsArray($faq->items);
        $this->assertCount(3, $faq->items);
    }

    public function test_faq_items_have_question_and_answer(): void
    {
        $faq = $this->seedFaq();

        foreach ($faq->items as $item) {
            $this->assertArrayHasKey('question', $item);
            $this->assertArrayHasKey('answer', $item);
            $this->assertNotEmpty($item['question']);
            $this->assertNotEmpty($item['answer']);
        }
    }

    public function test_faq_can_be_updated(): void
    {
        $faq = $this->seedFaq();

        $faq->update([
            'items' => [
                ['question' => 'Updated question?', 'answer' => 'Updated answer.'],
            ],
        ]);

        $faq->refresh();
        $this->assertCount(1, $faq->items);
        $this->assertEquals('Updated question?', $faq->items[0]['question']);
    }

    // ─── Homepage Display Tests ───────────────────────────────────────

    public function test_homepage_displays_faq_section_when_data_exists(): void
    {
        $this->seedFaq();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Frequently Asked Questions');
        $response->assertSee('What is Vaidyog?');
        $response->assertSee('Is registration free?');
    }

    public function test_homepage_hides_faq_section_when_no_data(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Frequently Asked Questions');
    }
}
