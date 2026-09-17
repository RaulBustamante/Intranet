<?php

namespace App\Domain\Requests\Models;

use App\Domain\People\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestComment extends Model
{
    protected $fillable = ['request_id', 'employee_id', 'body'];

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
}
