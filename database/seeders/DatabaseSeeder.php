<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@hostdevpro.app.br'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Admin@2026'),
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            ClientSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
