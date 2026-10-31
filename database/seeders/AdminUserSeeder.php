<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Creates the first admin account.
 * Email and password come from .env (ADMIN_EMAIL, ADMIN_PASSWORD) so they never go to GitHub.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@europeanit-inst.com')],
            ['name' => 'Admin', 'password' => env('ADMIN_PASSWORD')],
        );

        // role is not fillable, so set it directly
        $admin->role = 'admin';
        $admin->save();
    }
}
