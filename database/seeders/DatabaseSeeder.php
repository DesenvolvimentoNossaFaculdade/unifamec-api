<?php

namespace Database\Seeders;

use App\Models\User;
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
        $this->call([

            SiteInfoSeeder::class,

            PermissionSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            NewsSeeder::class,
            PageSeeder::class,
            StatisticSeeder::class,
            HeroSlideSeeder::class,
            NavigationSeeder::class,
            DocumentSeeder::class,
        ]);
    }
}
