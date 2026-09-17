<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    protected $fillable = ['poll_id', 'option_id', 'employee_id'];
}
