<?php

namespace App\Livewire\Admin\JobSeekers;

use App\Enums\AuthProviderEnum;
use App\Enums\UserStatusEnum;
use App\Models\JobCategory;
use App\Models\JobSeekerProfile;
use App\Models\JobSubcategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class JobSeekerBulkImport extends Component
{
    use WithFileUploads;

    public $csvFile = null;
    public bool $processing = false;
    public bool $processed = false;
    public array $results = [];
    public array $importErrors = [];
    public int $successCount = 0;
    public int $failCount = 0;
    public int $totalRows = 0;

    public function updatedCsvFile(): void
    {
        $this->validate([
            'csvFile' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);
        $this->reset(['processed', 'results', 'importErrors', 'successCount', 'failCount', 'totalRows']);
    }

    public function import(): void
    {
        $this->validate([
            'csvFile' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $this->processing = true;
        $this->importErrors = [];
        $this->results = [];
        $this->successCount = 0;
        $this->failCount = 0;

        $path = $this->csvFile->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            $this->importErrors[] = ['row' => 0, 'message' => 'Unable to read the uploaded file.'];
            $this->processing = false;
            return;
        }

        $header = fgetcsv($handle);

        if (!$header) {
            $this->importErrors[] = ['row' => 0, 'message' => 'CSV file is empty or has no header row.'];
            fclose($handle);
            $this->processing = false;
            return;
        }

        $header = array_map(fn ($h) => strtolower(trim($h)), $header);
        $requiredColumns = ['name', 'phone', 'category_slug', 'subcategory_name'];
        $missing = array_diff($requiredColumns, $header);

        if (!empty($missing)) {
            $this->importErrors[] = ['row' => 0, 'message' => 'Missing required columns: ' . implode(', ', $missing)];
            fclose($handle);
            $this->processing = false;
            $this->processed = true;
            return;
        }

        $categories = JobCategory::where('is_active', true)->pluck('id', 'slug');
        $subcategories = JobSubcategory::where('is_active', true)->get()->groupBy('job_category_id');
        $categoryNames = JobCategory::where('is_active', true)->pluck('name', 'slug');

        $rowNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            $this->totalRows++;

            if (count($row) !== count($header)) {
                $this->importErrors[] = ['row' => $rowNumber, 'message' => 'Column count mismatch.'];
                $this->failCount++;
                continue;
            }

            $data = array_combine($header, $row);
            $data = array_map('trim', $data);

            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'phone' => 'required|regex:/^[6-9]\d{9}$/|unique:users,phone',
                'email' => 'nullable|email|unique:users,email',
                'category_slug' => 'required|exists:job_categories,slug',
                'subcategory_name' => 'required|string',
            ], [
                'phone.regex' => 'Invalid 10-digit mobile number.',
                'phone.unique' => 'Phone already exists.',
                'email.unique' => 'Email already exists.',
            ]);

            if ($validator->fails()) {
                $this->importErrors[] = [
                    'row' => $rowNumber,
                    'message' => implode(' | ', $validator->errors()->all()),
                ];
                $this->failCount++;
                continue;
            }

            $categorySlug = $data['category_slug'];
            $categoryId = $categories[$categorySlug] ?? null;

            if (!$categoryId) {
                $this->importErrors[] = ['row' => $rowNumber, 'message' => "Category '{$categorySlug}' not found."];
                $this->failCount++;
                continue;
            }

            $catSubcategories = $subcategories[$categoryId] ?? collect();
            $subcategory = $catSubcategories->firstWhere('name', $data['subcategory_name']);

            if (!$subcategory) {
                $this->importErrors[] = ['row' => $rowNumber, 'message' => "Subcategory '{$data['subcategory_name']}' not found in category '{$categorySlug}'."];
                $this->failCount++;
                continue;
            }

            try {
                DB::transaction(function () use ($data, $categorySlug, $categoryNames, $subcategory) {
                    $user = User::create([
                        'name' => $data['name'],
                        'phone' => $data['phone'],
                        'email' => !empty($data['email']) ? $data['email'] : null,
                        'password' => null,
                        'user_type' => 'user',
                        'status' => 'active',
                        'auth_provider' => $data['auth_provider'] ?? 'phone',
                        'is_active' => true,
                        'is_profile_completed' => false,
                        'phone_verified_at' => null,
                    ]);

                    JobSeekerProfile::create([
                        'user_id' => $user->id,
                        'category_slug' => $categorySlug,
                        'category_name' => $categoryNames[$categorySlug] ?? $categorySlug,
                        'subcategory_name' => $subcategory->name,
                        'gender' => !empty($data['gender']) ? $data['gender'] : null,
                        'city' => !empty($data['city']) ? $data['city'] : null,
                        'state' => !empty($data['state']) ? $data['state'] : null,
                        'pincode' => !empty($data['pincode']) ? $data['pincode'] : null,
                        'experience_years' => !empty($data['experience_years']) ? $data['experience_years'] : null,
                        'highest_qualification' => !empty($data['highest_qualification']) ? $data['highest_qualification'] : null,
                        'current_employer' => !empty($data['current_employer']) ? $data['current_employer'] : null,
                        'created_by_admin_id' => Auth::guard('admin')->id(),
                    ]);
                });

                $this->successCount++;
            } catch (\Throwable $e) {
                $this->importErrors[] = ['row' => $rowNumber, 'message' => 'Database error: ' . $e->getMessage()];
                $this->failCount++;
            }
        }

        fclose($handle);
        $this->processing = false;
        $this->processed = true;
    }

    public function downloadTemplate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'name', 'phone', 'email', 'category_slug', 'subcategory_name',
                'auth_provider', 'gender', 'city', 'state', 'pincode',
                'experience_years', 'highest_qualification', 'current_employer',
            ]);
            fputcsv($handle, [
                'Rahul Sharma', '9876543210', 'rahul@example.com', 'doctors', 'General Physician',
                'phone', 'male', 'Mumbai', 'Maharashtra', '400001',
                '5', 'MBBS', 'City Hospital',
            ]);
            fclose($handle);
        }, 'job_seekers_import_template.csv');
    }

    public function resetImport(): void
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.job-seekers.job-seeker-bulk-import');
    }
}
