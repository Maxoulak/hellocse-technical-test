<?php

namespace Database\Seeders;

use App\Enums\ProfileStatus;
use App\Models\Admin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = Admin::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@hellocse.fr',
            'password' => Hash::make('admin'),
        ]);

        $profile = Profile::factory()->for($admin)->create([
            'lastname' => 'Bar',
            'firstname' => 'Foo',
            'status' => ProfileStatus::ACTIVE,
        ]);

        Comment::factory()->for($admin)->for($profile)->create([
            'content' => 'Awesome profile, hire him!',
        ]);
    }
}
