<?php

namespace App\Livewire\Recruiter\Dashboard;

use App\Models\Event;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\News;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'Dashboard'])]
class RecruiterDashboard extends Component
{
    public function render()
    {
        $recruiterId = auth()->id();

        // Metrics
        $liveJobs = JobPosting::forRecruiter($recruiterId)
            ->approved()->active()->notExpired()->count();

        $pendingJobs = JobPosting::forRecruiter($recruiterId)
            ->pending()->count();

        $expiredJobs = JobPosting::forRecruiter($recruiterId)
            ->expired()->count();

        $totalApplicants = JobApplication::where('recruiter_id', $recruiterId)->count();

        // Last 10 jobs
        $recentJobs = JobPosting::forRecruiter($recruiterId)
            ->withCount('applications')
            ->latest()
            ->take(10)
            ->get();

        // News (published)
        $news = News::published()
            ->with('category')
            ->latest('published_at')
            ->take(4)
            ->get();

        // Events (published)
        $events = Event::published()
            ->with('category')
            ->orderBy('event_date', 'desc')
            ->take(4)
            ->get();

        // Chart data: Jobs posted per month (last 6 months)
        $jobsPerMonth = JobPosting::forRecruiter($recruiterId)
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months
        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $chartLabels[] = $date->format('M Y');
            $chartData[] = $jobsPerMonth[$key] ?? 0;
        }

        // Applications per status (for pie/doughnut chart)
        $applicationsByStatus = JobApplication::where('recruiter_id', $recruiterId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('livewire.recruiter.dashboard.recruiter-dashboard', [
            'liveJobs' => $liveJobs,
            'pendingJobs' => $pendingJobs,
            'expiredJobs' => $expiredJobs,
            'totalApplicants' => $totalApplicants,
            'recentJobs' => $recentJobs,
            'news' => $news,
            'events' => $events,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'applicationsByStatus' => $applicationsByStatus,
        ]);
    }
}
