<?php

namespace App\Livewire\JobSeeker\Profile\SubModels;

use App\Models\UserCertification;
use Livewire\Component;

class CertificationManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public ?string $completion_date = null;
    public string $certification_id = '';
    public string $certification_url = '';
    public string $medical_institute = '';
    public bool $no_expiry = true;
    public ?string $validity_start = null;
    public ?string $validity_end = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|max:150',
            'completion_date' => 'nullable|date',
            'certification_id' => 'nullable|max:100',
            'certification_url' => 'nullable|url|max:255',
            'medical_institute' => 'nullable|max:150',
            'validity_start' => 'nullable|date',
            'validity_end' => 'nullable|date|after:validity_start',
        ];
    }

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $cert = UserCertification::where('user_id', auth()->id())->findOrFail($id);
            $this->editingId = $id;
            $this->name = $cert->name;
            $this->completion_date = $cert->completion_date?->format('Y-m-d');
            $this->certification_id = $cert->certification_id ?? '';
            $this->certification_url = $cert->certification_url ?? '';
            $this->medical_institute = $cert->medical_institute ?? '';
            $this->no_expiry = $cert->no_expiry;
            $this->validity_start = $cert->validity_start?->format('Y-m-d');
            $this->validity_end = $cert->validity_end?->format('Y-m-d');
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        UserCertification::updateOrCreate(
            ['id' => $this->editingId, 'user_id' => auth()->id()],
            [
                'user_id' => auth()->id(),
                'name' => $this->name,
                'completion_date' => $this->completion_date ?: null,
                'certification_id' => $this->certification_id ?: null,
                'certification_url' => $this->certification_url ?: null,
                'medical_institute' => $this->medical_institute ?: null,
                'no_expiry' => $this->no_expiry,
                'validity_start' => $this->no_expiry ? null : ($this->validity_start ?: null),
                'validity_end' => $this->no_expiry ? null : ($this->validity_end ?: null),
            ]
        );

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        UserCertification::where('user_id', auth()->id())->where('id', $id)->delete();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->completion_date = null;
        $this->certification_id = '';
        $this->certification_url = '';
        $this->medical_institute = '';
        $this->no_expiry = true;
        $this->validity_start = null;
        $this->validity_end = null;
        $this->resetValidation();
    }

    public function render()
    {
        $certifications = UserCertification::where('user_id', auth()->id())->latest()->get();
        return view('livewire.job-seeker.profile.sub-models.certification-manager', compact('certifications'));
    }
}
