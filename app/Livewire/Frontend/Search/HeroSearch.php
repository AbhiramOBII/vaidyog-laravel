<?php

namespace App\Livewire\Frontend\Search;

use App\Models\Designation;
use App\Models\Specialty;
use Livewire\Component;

class HeroSearch extends Component
{
    public string $query = '';
    public string $location = '';
    public string $experience = '';

    public array $querySuggestions = [];
    public array $locationSuggestions = [];
    public bool $showQueryDropdown = false;
    public bool $showLocationDropdown = false;

    private array $popularCities = ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Hyderabad', 'Pune', 'Kolkata', 'Ahmedabad', 'Jaipur', 'Lucknow', 'Remote'];

    public function updatedQuery(): void
    {
        if (strlen($this->query) >= 2) {
            $designations = Designation::active()
                ->where('name', 'like', "%{$this->query}%")
                ->orderBy('sort_order')
                ->limit(4)
                ->pluck('name')
                ->toArray();

            $specialties = Specialty::active()
                ->where('name', 'like', "%{$this->query}%")
                ->ordered()
                ->limit(3)
                ->pluck('name')
                ->toArray();

            $this->querySuggestions = array_unique(array_merge($designations, $specialties));
            $this->showQueryDropdown = count($this->querySuggestions) > 0;
        } else {
            $this->showQueryDropdown = false;
        }
    }

    public function updatedLocation(): void
    {
        if (strlen($this->location) >= 1) {
            $this->locationSuggestions = collect($this->popularCities)
                ->filter(fn($city) => stripos($city, $this->location) !== false)
                ->values()
                ->take(6)
                ->toArray();
            $this->showLocationDropdown = count($this->locationSuggestions) > 0;
        } else {
            $this->showLocationDropdown = false;
        }
    }

    public function selectQuery(string $value): void
    {
        $this->query = $value;
        $this->showQueryDropdown = false;
    }

    public function selectLocation(string $value): void
    {
        $this->location = $value;
        $this->showLocationDropdown = false;
    }

    public function search(): void
    {
        $params = array_filter([
            'search' => $this->query,
            'city' => $this->location,
            'exp' => $this->experience,
        ]);

        $this->redirect(route('jobs.index', $params));
    }

    public function render()
    {
        return view('livewire.frontend.search.hero-search');
    }
}
