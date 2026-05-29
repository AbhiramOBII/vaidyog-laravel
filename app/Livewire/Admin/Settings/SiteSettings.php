<?php

namespace App\Livewire\Admin\Settings;

use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin', ['pageTitle' => 'Site Settings'])]
class SiteSettings extends Component
{
    use WithFileUploads;

    // General
    public string $site_name = '';
    public string $site_tagline = '';
    public string $contact_email = '';
    public string $contact_phone = '';
    public string $contact_address = '';

    // SEO
    public string $meta_title = '';
    public string $meta_description = '';
    public string $meta_keywords = '';
    public string $google_analytics_id = '';
    public string $google_search_console = '';

    // Social
    public string $social_facebook = '';
    public string $social_twitter = '';
    public string $social_linkedin = '';
    public string $social_instagram = '';
    public string $social_youtube = '';

    // Appearance
    public string $footer_text = '';
    public string $announcement_bar = '';
    public bool $announcement_active = false;

    // Logo uploads
    public $logo = null;
    public $favicon = null;
    public ?string $existing_logo = null;
    public ?string $existing_favicon = null;

    public bool $showSuccess = false;
    public string $activeTab = 'general';

    public function mount(): void
    {
        $settings = SiteSetting::all()->pluck('value', 'key');

        $this->site_name = $settings['site_name'] ?? 'Vaidyog';
        $this->site_tagline = $settings['site_tagline'] ?? '';
        $this->contact_email = $settings['contact_email'] ?? '';
        $this->contact_phone = $settings['contact_phone'] ?? '';
        $this->contact_address = $settings['contact_address'] ?? '';

        $this->meta_title = $settings['meta_title'] ?? '';
        $this->meta_description = $settings['meta_description'] ?? '';
        $this->meta_keywords = $settings['meta_keywords'] ?? '';
        $this->google_analytics_id = $settings['google_analytics_id'] ?? '';
        $this->google_search_console = $settings['google_search_console'] ?? '';

        $this->social_facebook = $settings['social_facebook'] ?? '';
        $this->social_twitter = $settings['social_twitter'] ?? '';
        $this->social_linkedin = $settings['social_linkedin'] ?? '';
        $this->social_instagram = $settings['social_instagram'] ?? '';
        $this->social_youtube = $settings['social_youtube'] ?? '';

        $this->footer_text = $settings['footer_text'] ?? '';
        $this->announcement_bar = $settings['announcement_bar'] ?? '';
        $this->announcement_active = (bool) ($settings['announcement_active'] ?? false);

        $this->existing_logo = $settings['site_logo'] ?? null;
        $this->existing_favicon = $settings['site_favicon'] ?? null;
    }

    public function save(): void
    {
        $this->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:500',
            'google_analytics_id' => 'nullable|string|max:50',
            'google_search_console' => 'nullable|string|max:100',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'footer_text' => 'nullable|string|max:500',
            'announcement_bar' => 'nullable|string|max:500',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:512',
        ]);

        // General
        SiteSetting::set('site_name', $this->site_name, 'general');
        SiteSetting::set('site_tagline', $this->site_tagline, 'general');
        SiteSetting::set('contact_email', $this->contact_email, 'general');
        SiteSetting::set('contact_phone', $this->contact_phone, 'general');
        SiteSetting::set('contact_address', $this->contact_address, 'general');

        // SEO
        SiteSetting::set('meta_title', $this->meta_title, 'seo');
        SiteSetting::set('meta_description', $this->meta_description, 'seo');
        SiteSetting::set('meta_keywords', $this->meta_keywords, 'seo');
        SiteSetting::set('google_analytics_id', $this->google_analytics_id, 'seo');
        SiteSetting::set('google_search_console', $this->google_search_console, 'seo');

        // Social
        SiteSetting::set('social_facebook', $this->social_facebook, 'social');
        SiteSetting::set('social_twitter', $this->social_twitter, 'social');
        SiteSetting::set('social_linkedin', $this->social_linkedin, 'social');
        SiteSetting::set('social_instagram', $this->social_instagram, 'social');
        SiteSetting::set('social_youtube', $this->social_youtube, 'social');

        // Appearance
        SiteSetting::set('footer_text', $this->footer_text, 'appearance');
        SiteSetting::set('announcement_bar', $this->announcement_bar, 'appearance');
        SiteSetting::set('announcement_active', $this->announcement_active ? '1' : '0', 'appearance');

        // File uploads
        if ($this->logo) {
            $path = $this->logo->store('site', 'public');
            SiteSetting::set('site_logo', $path, 'appearance');
            $this->existing_logo = $path;
            $this->logo = null;
        }

        if ($this->favicon) {
            $path = $this->favicon->store('site', 'public');
            SiteSetting::set('site_favicon', $path, 'appearance');
            $this->existing_favicon = $path;
            $this->favicon = null;
        }

        $this->showSuccess = true;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.settings.site-settings');
    }
}
