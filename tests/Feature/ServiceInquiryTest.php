<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\StudioSetting;
use App\Models\User;
use App\Support\StudioServices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ServiceInquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_pages_have_distinct_validated_presets_and_unknown_services_are_not_found(): void
    {
        $this->withoutVite();
        foreach (StudioServices::all() as $service) {
            $this->get('/servicios/'.$service['slug'])->assertOk()->assertInertia(fn (Assert $page) => $page->component('Service')->where('service.slug', $service['slug'])->where('service.subject', $service['subject'])->where('service.message', $service['message']));
        }
        $this->get('/servicios/nonexistent')->assertNotFound();
    }

    public function test_service_inquiry_is_private_and_requires_consent_and_valid_subject(): void
    {
        $service = StudioServices::all()[1];
        $payload = ['name' => 'Consulta local de servicio', 'email' => 'service-review@example.test', 'subject' => $service['subject'], 'message' => $service['message'], 'privacy' => true];
        $this->post('/contacto', [...$payload, 'privacy' => false])->assertSessionHasErrors('privacy');
        $this->post('/contacto', [...$payload, 'subject' => 'untrusted'])->assertSessionHasErrors('subject');
        $this->assertDatabaseCount('inquiries', 0);
        $this->post('/contacto', $payload)->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame($service['message'], Inquiry::first()->message);
        $this->assertNotSame($service['message'], DB::table('inquiries')->value('message'));
        $this->assertDatabaseCount('jobs', 1);
    }

    public function test_social_placeholders_can_be_saved_but_unsafe_links_are_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $data = [...StudioSetting::defaults(), 'currency' => 'DOP', 'instagram_url' => '/#', 'facebook_url' => '/#', 'youtube_url' => 'https://www.youtube.com/'];
        $this->actingAs($admin)->post('/admin/settings', $data)->assertRedirect()->assertSessionHasNoErrors();
        $this->assertSame('/#', StudioSetting::current()->instagram_url);
        foreach (['javascript:alert(1)', '//example.test', 'http://example.test', '/admin'] as $unsafe) {
            $this->post('/admin/settings', [...$data, 'instagram_url' => $unsafe])->assertSessionHasErrors('instagram_url');
        }
    }
}
