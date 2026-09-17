<?php

namespace App\Domain\Content\Models;

use App\Domain\Content\Models\Concerns\HasLocalizedName;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    use HasLocalizedName;

    protected $fillable = ['name_es', 'name_en', 'slug', 'visibility', 'required_role', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'category_id');
    }

    public function getNameAttribute(): string
    {
        return $this->localized('name');
    }

    /** ¿Puede este usuario ver esta categoría? (DOC-02) */
    public function visibleTo(?User $user): bool
    {
        return match ($this->visibility) {
            'role' => $user && $this->required_role && $user->hasRole($this->required_role),
            default => true,   // 'all' (y 'location' se afina en Fase futura)
        };
    }
}
