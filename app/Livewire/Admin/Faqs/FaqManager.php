<?php

namespace App\Livewire\Admin\Faqs;

use App\Models\Faq;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'FAQs'])]
class FaqManager extends Component
{
    public array $faqs = [];

    public function mount(): void
    {
        $faq = Faq::first();
        if ($faq && is_array($faq->items)) {
            $this->faqs = $faq->items;
        } else {
            $this->faqs = [
                ['id' => uniqid(), 'question' => '', 'answer' => ''],
            ];
        }
    }

    public function addMore(): void
    {
        $this->faqs[] = ['id' => uniqid(), 'question' => '', 'answer' => ''];
    }

    public function remove(int $index): void
    {
        if (count($this->faqs) > 1) {
            unset($this->faqs[$index]);
            $this->faqs = array_values($this->faqs);
        }
    }

    public function moveUp(int $index): void
    {
        if ($index > 0) {
            $temp = $this->faqs[$index];
            $this->faqs[$index] = $this->faqs[$index - 1];
            $this->faqs[$index - 1] = $temp;
        }
    }

    public function moveDown(int $index): void
    {
        if ($index < count($this->faqs) - 1) {
            $temp = $this->faqs[$index];
            $this->faqs[$index] = $this->faqs[$index + 1];
            $this->faqs[$index + 1] = $temp;
        }
    }

    public function save(): void
    {
        $this->validate([
            'faqs' => 'required|array|min:1',
            'faqs.*.question' => 'required|string|max:500',
            'faqs.*.answer' => 'required|string|max:5000',
        ], [
            'faqs.*.question.required' => 'Question is required.',
            'faqs.*.answer.required' => 'Answer is required.',
        ]);

        // Re-assign sequential IDs
        foreach ($this->faqs as $index => &$faq) {
            $faq['id'] = $index + 1;
        }
        unset($faq);

        $record = Faq::first();
        if ($record) {
            $record->update(['items' => $this->faqs]);
        } else {
            Faq::create(['items' => $this->faqs]);
        }

        session()->flash('success', 'FAQs saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.faqs.faq-manager');
    }
}
