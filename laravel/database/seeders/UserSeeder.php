<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Crea la cuenta administradora del panel.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Forcha Admin',
            'email' => 'admin@forchasport.com',
        ]);
    }
}
