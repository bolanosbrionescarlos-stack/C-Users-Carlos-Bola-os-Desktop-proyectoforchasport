<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MakeAdminUser extends Command
{
    protected $signature = 'admin:create
                            {--name= : Nombre del administrador}
                            {--email= : Correo electrónico}
                            {--password= : Contraseña (mínimo 8 caracteres)}
                            {--force : Actualizar si ya existe}';

    protected $description = 'Crear o actualizar un usuario administrador de forma segura';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Nombre del administrador');
        $email = $this->option('email') ?: $this->ask('Correo electrónico');
        $password = $this->option('password') ?: $this->secret('Contraseña (mínimo 8 caracteres)');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $existing = User::where('email', $email)->first();

        if ($existing && ! $this->option('force')) {
            $this->error('Ya existe un usuario con ese correo. Usa --force para actualizarlo a administrador.');

            return self::FAILURE;
        }

        if ($existing) {
            $existing->forceFill([
                'name' => $name,
                'password' => Hash::make($password),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => $existing->email_verified_at ?? now(),
            ])->save();

            $this->info('Usuario administrador actualizado correctamente.');

            return self::SUCCESS;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        $this->info('Usuario administrador creado correctamente.');

        return self::SUCCESS;
    }
}
