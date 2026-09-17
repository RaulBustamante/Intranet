<?php

namespace Database\Seeders;

use App\Domain\People\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Usuarios de prueba para desarrollo (NO se corre en producción).
 *
 * Crea tres cuentas, cada una ligada a una persona real del directorio
 * importado, para poder probar los tres niveles de acceso:
 *   - admin@localhost        rol admin
 *   - rh@localhost           rol hr_editor (ve el panel de RH)
 *   - empleado@localhost     rol employee (NO ve el panel)
 *
 * Contraseña de todas: "password". Solo local.
 */
class DevUsersSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->warn('DevUsersSeeder no corre en producción. Omitido.');

            return;
        }

        $cuentas = [
            ['email' => 'admin@localhost',    'name' => 'Admin',    'rol' => 'admin'],
            ['email' => 'rh@localhost',       'name' => 'RH',       'rol' => 'hr_editor'],
            ['email' => 'empleado@localhost', 'name' => 'Empleado', 'rol' => 'employee'],
        ];

        foreach ($cuentas as $i => $c) {
            $user = User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name'      => $c['name'],
                    'password'  => Hash::make('password'),
                    'is_active' => true,
                    'locale'    => 'es',
                    // Liga a una persona real del directorio, si hay
                    'employee_id' => Employee::query()->skip($i)->value('id'),
                ]
            );

            $user->syncRoles([$c['rol']]);
        }

        $this->command->info('3 usuarios de desarrollo: admin@localhost / rh@localhost / empleado@localhost (contraseña: password)');
    }
}
