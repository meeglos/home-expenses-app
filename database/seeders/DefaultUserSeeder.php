<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si el usuario ya existe
        $existingUser = User::where('email', 'miguel@example.test')->first();
        
        if (!$existingUser) {
            User::create([
                'name' => 'Miguel Rodriguez',
                'email' => 'miguel@example.test',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
            
            $this->command->info('Usuario por defecto creado: Miguel Rodriguez (miguel@example.test)');
        } else {
            $this->command->info('El usuario miguel@example.test ya existe, saltando creación.');
        }
    }
}
