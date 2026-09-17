<?php

namespace App\Domain\Content\Models;

use App\Domain\Content\Models\Concerns\HasLocalizedName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use SoftDeletes, HasLocalizedName;

    protected $fillable = [
        'title_es', 'title_en', 'category_id', 's3_key', 'version_no',
        'mime_type', 'original_name', 'content_text', 'size_bytes',
        'is_private', 'uploaded_by', 'published_at',
    ];

    protected function casts(): array
    {
        return ['is_private' => 'boolean', 'published_at' => 'datetime'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    /** Historial de versiones, la más nueva primero (DOC-04). */
    public function versions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DocumentVersion::class)->orderByDesc('version_no');
    }

    public function getTitleAttribute(): string
    {
        return $this->localized('title');
    }

    /**
     * URL de descarga. Los privados van por URL firmada con vencimiento (DOC-09);
     * los públicos por CloudFront.
     */
    public function url(): string
    {
        if ($this->is_private) {
            return Storage::disk('s3')->temporaryUrl($this->s3_key, now()->addMinutes(15));
        }

        return Storage::disk('s3')->url($this->s3_key);
    }
}
