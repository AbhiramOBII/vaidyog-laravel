<?php

namespace App\Livewire\JobSeeker\Profile\SubModels;

use App\Models\UserEducation;
use Livewire\Component;

class EducationManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $degree = '';
    public string $university = '';
    public string $course = '';
    public string $specialization = '';
    public string $course_type = 'full_time';
    public ?int $course_duration_start = null;
    public ?int $course_duration_end = null;
    public string $grading_system = 'percentage';
    public string $grade = '';

    protected function rules(): array
    {
        return [
            'degree' => 'required|max:100',
            'university' => 'required|max:150',
            'course' => 'nullable|max:100',
            'specialization' => 'nullable|max:100',
            'course_type' => 'required|in:full_time,part_time,distance_learning,online',
            'course_duration_start' => 'required|integer|between:1950,' . date('Y'),
            'course_duration_end' => 'nullable|integer|gte:course_duration_start',
            'grading_system' => 'required|in:percentage,cgpa,grade',
            'grade' => 'nullable|max:20',
        ];
    }

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $edu = UserEducation::where('user_id', auth()->id())->findOrFail($id);
            $this->editingId = $id;
            $this->degree = $edu->degree;
            $this->university = $edu->university ?? '';
            $this->course = $edu->course ?? '';
            $this->specialization = $edu->specialization ?? '';
            $this->course_type = $edu->course_type ?? 'full_time';
            $this->course_duration_start = $edu->course_duration_start;
            $this->course_duration_end = $edu->course_duration_end;
            $this->grading_system = $edu->grading_system ?? 'percentage';
            $this->grade = $edu->grade ?? '';
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        UserEducation::updateOrCreate(
            ['id' => $this->editingId, 'user_id' => auth()->id()],
            [
                'user_id' => auth()->id(),
                'degree' => $this->degree,
                'university' => $this->university ?: null,
                'course' => $this->course ?: null,
                'specialization' => $this->specialization ?: null,
                'course_type' => $this->course_type,
                'course_duration_start' => $this->course_duration_start,
                'course_duration_end' => $this->course_duration_end,
                'grading_system' => $this->grading_system,
                'grade' => $this->grade ?: null,
            ]
        );

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        UserEducation::where('user_id', auth()->id())->where('id', $id)->delete();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->degree = '';
        $this->university = '';
        $this->course = '';
        $this->specialization = '';
        $this->course_type = 'full_time';
        $this->course_duration_start = null;
        $this->course_duration_end = null;
        $this->grading_system = 'percentage';
        $this->grade = '';
        $this->resetValidation();
    }

    public function render()
    {
        $educations = UserEducation::where('user_id', auth()->id())->latest()->get();
        return view('livewire.job-seeker.profile.sub-models.education-manager', compact('educations'));
    }
}
