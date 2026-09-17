<?php

namespace App\Domain\Requests\Models;

use App\Domain\People\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestApproval extends Model
{
    protected $fillable = ['request_id', 'step', 'approver_id', 'approver_role', 'decision', 'comment', 'decided_at'];

    protected function casts(): array
    {
        return ['decided_at' => 'datetime'];
    }

    public function request(): BelongsTo  { return $this->belongsTo(ServiceRequest::class, 'request_id'); }
    public function approver(): BelongsTo  { return $this->belongsTo(Employee::class, 'approver_id'); }
}
