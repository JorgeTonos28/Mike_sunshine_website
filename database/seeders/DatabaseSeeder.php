<?php

namespace Database\Seeders;

use App\Models\StudioSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        StudioSetting::current();
        $this->call(LegalPageSeeder::class);
    }
}
