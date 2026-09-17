<?php

namespace App\Domain\Content\Models;

use App\Domain\People\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kudo extends Model
{
    protected $fillable = ['from_employee_id', 'to_employee_id', 'message', 'value_tag', 'approved_at', 'approved_by'];

    protected function casts(): array
    {
        return ['approved_at' => 'datetime'];
    }

    public function from(): BelongsTo { return $this->belongsTo(Employee::class, 'from_employee_id'); }
    public function to(): BelongsTo   { return $this->belongsTo(Employee::class, 'to_employee_id'); }

    public function scopeApproved(Builder $q): Builder
    {
        return $q->whereNotNull('approved_at');
    }
}
