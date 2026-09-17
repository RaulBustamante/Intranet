<?php

namespace Database\Seeders;

use App\Domain\Requests\Models\RequestType;
use Illuminate\Database\Seeder;

/**
 * Los cuatro tipos de solicitud (REQ-01).
 *
 * Los flujos de aprobación aquí son un DEFAULT razonable. RH y TI deben
 * confirmar los flujos reales (pendiente P7 / tarea 5.7): quién aprueba qué y
 * en cuántos pasos. Cambiar un flujo es editar approval_steps, sin código.
 */
class RequestTypeSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'code' => 'vacation', 'name_es' => 'Vacaciones / Permiso', 'name_en' => 'Time off', 'icon' => 'calendar', 'sla_days' => 3,
                'field_schema' => [
                    ['key' => 'start_date', 'label_es' => 'Del', 'label_en' => 'From', 'type' => 'date', 'required' => true],
                    ['key' => 'end_date', 'label_es' => 'Al', 'label_en' => 'To', 'type' => 'date', 'required' => true],
                    ['key' => 'reason', 'label_es' => 'Motivo', 'label_en' => 'Reason', 'type' => 'textarea', 'required' => false],
                ],
                'approval_steps' => [['by' => 'manager'], ['by' => 'role', 'role' => 'hr_editor']],
            ],
            [
                'code' => 'it', 'name_es' => 'Soporte TI', 'name_en' => 'IT Support', 'icon' => 'lifebuoy', 'sla_days' => 2,
                'field_schema' => [
                    ['key' => 'category', 'label_es' => 'Categoría', 'label_en' => 'Category', 'type' => 'select', 'required' => true,
                     'options' => [['es' => 'Hardware', 'en' => 'Hardware'], ['es' => 'Software', 'en' => 'Software'], ['es' => 'Red', 'en' => 'Network'], ['es' => 'Cuenta', 'en' => 'Account']]],
                    ['key' => 'description', 'label_es' => 'Descripción', 'label_en' => 'Description', 'type' => 'textarea', 'required' => true],
                ],
                'approval_steps' => [['by' => 'role', 'role' => 'admin']],
            ],
            [
                'code' => 'maintenance', 'name_es' => 'Mantenimiento', 'name_en' => 'Maintenance', 'icon' => 'settings', 'sla_days' => 4,
                'field_schema' => [
                    ['key' => 'area', 'label_es' => 'Área o ubicación', 'label_en' => 'Area or location', 'type' => 'text', 'required' => true],
                    ['key' => 'description', 'label_es' => 'Descripción', 'label_en' => 'Description', 'type' => 'textarea', 'required' => true],
                ],
                'approval_steps' => [['by' => 'role', 'role' => 'admin']],
            ],
            [
                'code' => 'purchase', 'name_es' => 'Requisición de compra', 'name_en' => 'Purchase request', 'icon' => 'inbox', 'sla_days' => 5,
                'field_schema' => [
                    ['key' => 'item', 'label_es' => 'Artículo', 'label_en' => 'Item', 'type' => 'text', 'required' => true],
                    ['key' => 'quantity', 'label_es' => 'Cantidad', 'label_en' => 'Quantity', 'type' => 'number', 'required' => true],
                    ['key' => 'estimated_cost', 'label_es' => 'Costo estimado', 'label_en' => 'Estimated cost', 'type' => 'number', 'required' => false],
                    ['key' => 'justification', 'label_es' => 'Justificación', 'label_en' => 'Justification', 'type' => 'textarea', 'required' => true],
                ],
                'approval_steps' => [['by' => 'manager'], ['by' => 'role', 'role' => 'admin']],
            ],
        ];

        foreach ($tipos as $i => $t) {
            RequestType::updateOrCreate(['code' => $t['code']], $t + ['sort_order' => $i, 'is_active' => true]);
        }

        $this->command->info('4 tipos de solicitud (flujos por confirmar con RH — P7).');
    }
}
