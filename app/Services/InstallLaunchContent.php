<?php

namespace App\Services;

use App\Models\LegalPage;
use App\Models\SiteRelease;
use App\Models\StudioSetting;
use App\Support\LaunchContent;
use Illuminate\Support\Facades\DB;

class InstallLaunchContent
{
    public function install(bool $dryRun = false): bool
    {
        return DB::transaction(function () use ($dryRun) {
            if (SiteRelease::where('key', LaunchContent::KEY)->exists()) {
                return false;
            }
            if ($dryRun) {
                return true;
            }
            $settings = StudioSetting::current();
            $previous = [
                'settings' => $settings->only(array_keys(LaunchContent::settings())),
                'pages' => LegalPage::whereIn('slug', array_keys(LaunchContent::pages()))->get()->map->only(['slug', 'title', 'content_blocks', 'is_approved', 'version'])->all(),
            ];
            $settings->update(LaunchContent::settings());
            foreach (LaunchContent::pages() as $slug => $content) {
                $page = LegalPage::firstOrNew(['slug' => $slug]);
                $page->fill([...$content, 'is_approved' => true, 'version' => $page->exists ? $page->version + 1 : 1])->save();
            }
            SiteRelease::create(['key' => LaunchContent::KEY, 'previous_content' => $previous, 'installed_at' => now()]);

            return true;
        });
    }
}
