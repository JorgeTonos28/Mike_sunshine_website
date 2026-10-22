<?php

namespace Tests\Feature;

use App\Models\StudioSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ArtistPortraitTest extends TestCase
{
    use RefreshDatabase;

    private function settings(): array
    {
        return ['name' => 'Mike Sunshine', 'currency' => 'DOP', 'hero_title' => 'Mike Sunshine'];
    }

    public function test_admin_can_replace_portrait_with_a_reencoded_raster_and_it_is_shared_publicly(): void
    {
        $this->withoutVite();
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->post('/admin/settings', [...$this->settings(), 'portrait' => UploadedFile::fake()->image('mike.png', 200, 250)])->assertSessionHasNoErrors()->assertRedirect();
        $first = StudioSetting::current()->portrait_path;
        $this->assertMatchesRegularExpression('~^/media/[a-f0-9-]{36}\.webp$~', $first);
        $this->assertSame('image/webp', getimagesizefromstring(Storage::disk('public')->get(basename($first)))['mime']);
        $this->get('/')->assertInertia(fn (Assert $page) => $page->where('studio.portrait_path', $first));

        $this->post('/admin/settings', [...$this->settings(), 'portrait' => UploadedFile::fake()->image('replacement.jpg', 240, 300)])->assertSessionHasNoErrors();
        $this->assertNotSame($first, StudioSetting::current()->portrait_path);
        Storage::disk('public')->assertExists(basename(StudioSetting::current()->portrait_path));
    }

    public function test_untrusted_paths_and_invalid_portrait_files_cannot_replace_the_saved_image(): void
    {
        Storage::fake('public');
        $original = StudioSetting::current()->portrait_path;
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->post('/admin/settings', [...$this->settings(), 'portrait_path' => 'https://untrusted.test/tracker.svg'])->assertSessionHasNoErrors();
        $this->assertSame($original, StudioSetting::current()->portrait_path);
        foreach ([UploadedFile::fake()->createWithContent('fake.png', '<svg onload="alert(1)"/>'), UploadedFile::fake()->image('large.png', 30, 30)->size(8193)] as $file) {
            $this->post('/admin/settings', [...$this->settings(), 'portrait' => $file])->assertSessionHasErrors('portrait');
            $this->assertSame($original, StudioSetting::current()->portrait_path);
        }
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_guests_and_editors_cannot_replace_the_artist_portrait(): void
    {
        Storage::fake('public');
        $original = StudioSetting::current()->portrait_path;
        $this->post('/admin/settings', [...$this->settings(), 'portrait' => UploadedFile::fake()->image('guest.png')])->assertRedirect('/login');
        $editor = User::factory()->create(['role' => 'editor']);
        $this->actingAs($editor)->post('/admin/settings', [...$this->settings(), 'portrait' => UploadedFile::fake()->image('editor.png')])->assertForbidden();
        $this->assertSame($original, StudioSetting::current()->portrait_path);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }
}
