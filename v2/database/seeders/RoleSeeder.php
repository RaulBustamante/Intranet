<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Los 5 roles del proyecto (AUTH-05).
 *
 * Se definen por lo que cada uno puede VER Y HACER, no por jerarquía:
 *   - employee       cualquier empleado autenticado; solo consume
 *   - hr_editor      administra empleados, catálogos y fotos (el panel de RH)
 *   - content_editor publica documentos, boletines, anuncios
 *   - approver       resuelve solicitudes en su bandeja (Fase 5)
 *   - admin          todo, incluidos ajustes y auditoría
 *
 * Un mismo usuario puede tener varios roles. El admin no "hereda" a los
 * demás: las Policies preguntan por el rol concreto, siguiendo el patrón que
 * ya se usa en pm-ariel.
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['employee', 'hr_editor', 'content_editor', 'approver', 'admin'] as $rol) {
            Role::findOrCreate($rol, 'web');
        }
    }
}
