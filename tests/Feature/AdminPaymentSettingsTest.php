<?php

namespace Tests\Feature;

use App\Livewire\Admin\Settings\SiteSettings;
use App\Models\Admin;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPaymentSettingsTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => 'password',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_access_settings_page(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.settings'))
            ->assertOk();
    }

    public function test_admin_can_view_payment_tab(): void
    {
        Livewire::actingAs($this->admin, 'admin')
            ->test(SiteSettings::class)
            ->call('setTab', 'payment')
            ->assertSet('activeTab', 'payment')
            ->assertSee('Razorpay Configuration');
    }

    public function test_admin_can_save_razorpay_credentials(): void
    {
        Livewire::actingAs($this->admin, 'admin')
            ->test(SiteSettings::class)
            ->set('razorpay_key_id', 'rzp_test_abc123')
            ->set('razorpay_key_secret', 'secret_xyz789')
            ->set('razorpay_webhook_secret', 'whsec_test123')
            ->call('save')
            ->assertSet('showSuccess', true);

        $this->assertEquals('rzp_test_abc123', SiteSetting::get('razorpay_key_id'));
        $this->assertEquals('secret_xyz789', SiteSetting::get('razorpay_key_secret'));
        $this->assertEquals('whsec_test123', SiteSetting::get('razorpay_webhook_secret'));
    }

    public function test_razorpay_credentials_persist_after_reload(): void
    {
        SiteSetting::set('razorpay_key_id', 'rzp_live_persist', 'payment');
        SiteSetting::set('razorpay_key_secret', 'secret_persist', 'payment');

        Livewire::actingAs($this->admin, 'admin')
            ->test(SiteSettings::class)
            ->assertSet('razorpay_key_id', 'rzp_live_persist')
            ->assertSet('razorpay_key_secret', 'secret_persist');
    }
}
