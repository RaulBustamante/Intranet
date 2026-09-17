<?php

namespace App\Domain\Content\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Una versión histórica de un documento (DOC-04).
 *
 * La descarga hereda la privacidad del documento padre: si el documento es
 * privado, cualquier versión suya va por URL firmada con vencimiento (DOC-09).
 */
class DocumentVersion extends Model
{
    protected $fillable = [
        'document_id', 'version_no', 's3_key', 'original_name',
        'mime_type', 'size_bytes', 'note', 'uploaded_by',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function url(): string
    {
        if ($this->document?->is_private) {
            return Storage::disk('s3')->temporaryUrl($this->s3_key, now()->addMinutes(15));
        }

        return Storage::disk('s3')->url($this->s3_key);
    }
}
