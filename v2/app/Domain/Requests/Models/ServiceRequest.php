<?php

namespace App\Domain\Requests\Models;

use App\Domain\People\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    protected $fillable = [
        'type_id', 'requester_id', 'payload', 'status', 'current_step',
        'submitted_at', 'due_at', 'closed_at',
    ];

    protected function casts(): array
    {
        return ['payload' => 'array', 'submitted_at' => 'datetime', 'due_at' => 'datetime', 'closed_at' => 'datetime'];
    }

    public function type(): BelongsTo      { return $this->belongsTo(RequestType::class, 'type_id'); }
    public function requester(): BelongsTo { return $this->belongsTo(Employee::class, 'requester_id'); }
    public function approvals(): HasMany   { return $this->hasMany(RequestApproval::class, 'request_id')->orderBy('step'); }
    public function comments(): HasMany    { return $this->hasMany(RequestComment::class, 'request_id')->latest(); }

    public function isOpen(): bool
    {
        return in_array($this->status, ['pending'], true);
    }

    /** ¿Está vencida? (pasó su compromiso de tiempo y sigue abierta) — REQ-08 */
    public function isOverdue(): bool
    {
        return $this->isOpen() && $this->due_at && $this->due_at->isPast();
    }
}
