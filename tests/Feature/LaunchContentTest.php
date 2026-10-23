<?php

namespace Tests\Feature;

use App\Models\LegalPage;
use App\Models\SiteRelease;
use App\Models\StudioSetting;
use App\Models\User;
use App\Services\InstallLaunchContent;
use App\Support\ContentBlocks;
use App\Support\LaunchContent;
use Database\Seeders\LegalPageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LaunchContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_release_publishes_three_policies_and_confirmed_contact_with_previous_content_saved(): void
    {
        $this->seed(LegalPageSeeder::class);
        $settings = StudioSetting::current();
        $settings->update(['contact_email' => 'previous@example.test', 'facebook_url' => '/#', 'youtube_url' => '/#']);
        $oldPrivacy = LegalPage::where('slug', 'privacidad')->firstOrFail()->content_blocks;
        $this->assertTrue(app(InstallLaunchContent::class)->install());
        $this->assertSame(LaunchContent::settings(), $settings->fresh()->only(array_keys(LaunchContent::settings())));
        $this->assertSame(3, LegalPage::where('is_approved', true)->where('version', 2)->count());
        $release = SiteRelease::sole();
        $this->assertSame('previous@example.test', $release->previous_content['settings']['contact_email']);
        $this->assertSame($oldPrivacy, collect($release->previous_content['pages'])->firstWhere('slug', 'privacidad')['content_blocks']);
        foreach (LegalPage::all() as $page) {
            $validator = Validator::make(['content_blocks' => $page->content_blocks], ContentBlocks::rules());
            $validator->after(ContentBlocks::validate(...));
            $this->assertTrue($validator->passes());
        }
        foreach (['users', 'orders', 'inquiries', 'jobs'] as $table) {
            $this->assertDatabaseCount($table, 0);
        }
    }

    public function test_repeated_install_preserves_subsequent_admin_edits(): void
    {
        app(InstallLaunchContent::class)->install();
        StudioSetting::current()->update(['contact_email' => 'edited@example.test']);
        $page = LegalPage::where('slug', 'privacidad')->firstOrFail();
        $page->update(['title' => 'Política revisada', 'version' => 3, 'is_approved' => false]);
        $this->assertFalse(app(InstallLaunchContent::class)->install());
        $this->assertSame('edited@example.test', StudioSetting::current()->contact_email);
        $this->assertSame('Política revisada', $page->fresh()->title);
        $this->assertFalse($page->fresh()->is_approved);
        $this->assertDatabaseCount('site_releases', 1);
    }

    public function test_production_command_has_a_non_mutating_preview_and_explicit_install(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $this->artisan('studio:launch-install')->assertFailed();
        $this->artisan('studio:launch-install', ['--dry-run' => true])->expectsOutputToContain('No se ha guardado nada')->assertSuccessful();
        $this->assertDatabaseCount('studio_settings', 0);
        $this->assertDatabaseCount('legal_pages', 0);
        $this->assertDatabaseCount('site_releases', 0);
        $this->artisan('studio:launch-install', ['--force' => true])->assertSuccessful();
        $this->artisan('studio:launch-install', ['--force' => true])->expectsOutputToContain('ya está instalado')->assertSuccessful();
        $this->assertDatabaseCount('legal_pages', 3);
    }

    public function test_public_pages_include_approved_policies_and_only_instagram(): void
    {
        $this->withoutVite();
        app(InstallLaunchContent::class)->install();
        foreach (['privacidad', 'terminos', 'cookies'] as $slug) {
            $this->get('/'.$slug)->assertOk()->assertInertia(fn ($page) => $page
                ->where('page.is_approved', true)->where('studio.legal_name', 'Michael Febles Quezada')
                ->where('studio.phone', '+1 849-428-8682')->where('studio.facebook_url', null)->where('studio.youtube_url', null)
                ->missing('previous_content'));
        }
    }

    public function test_admin_can_edit_public_legal_identity_but_editors_cannot(): void
    {
        app(InstallLaunchContent::class)->install();
        $payload = [...StudioSetting::current()->only(['name', 'tagline', 'bio', 'contact_email', 'phone', 'address', 'instagram_url', 'facebook_url', 'youtube_url', 'currency', 'hero_title', 'hero_description']), 'legal_name' => 'Identidad revisada'];
        $this->actingAs(User::factory()->create(['role' => 'editor']))->post('/admin/settings', $payload)->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'admin']))->post('/admin/settings', $payload)->assertSessionHasNoErrors();
        $this->assertSame('Identidad revisada', StudioSetting::current()->legal_name);
    }
}
