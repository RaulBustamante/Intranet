<?php

namespace App\Domain\Content\Models;

use App\Domain\Content\Models\Concerns\HasLocalizedName;
use Illuminate\Database\Eloquent\Model;

class Shortcut extends Model
{
    use HasLocalizedName;

    protected $fillable = [
        'label_es', 'label_en', 'icon', 'tint', 'target_type',
        'target', 'requires_auth', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['requires_auth' => 'boolean', 'is_active' => 'boolean'];
    }

    public function getLabelAttribute(): string
    {
        return $this->localized('label');
    }

    /** La URL de destino. Ruta interna resuelta, o URL externa tal cual. */
    public function href(): string
    {
        if ($this->target_type === 'url') {
            return $this->target;
        }

        // Ruta interna. Si no existe (typo), cae a la landing para no romper.
        return \Route::has($this->target) ? route($this->target) : url('/');
    }

    public function isExternal(): bool
    {
        return $this->target_type === 'url';
    }
}
