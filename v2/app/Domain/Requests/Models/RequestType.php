<?php

namespace App\Domain\Requests\Models;

use App\Domain\Content\Models\Concerns\HasLocalizedName;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasLocalizedName;

    protected $fillable = ['code', 'name_es', 'name_en', 'icon', 'field_schema', 'approval_steps', 'sla_days', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['field_schema' => 'array', 'approval_steps' => 'array', 'is_active' => 'boolean'];
    }

    public function getNameAttribute(): string { return $this->localized('name'); }

    /** Etiqueta de un campo del schema en el idioma activo. */
    public function fieldLabel(array $field): string
    {
        return $field['label_' . app()->getLocale()] ?? $field['label_es'] ?? $field['key'];
    }
}
