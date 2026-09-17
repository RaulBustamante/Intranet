<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementAudience extends Model
{
    public $timestamps = false;
    protected $fillable = ['announcement_id', 'location_id', 'department_id'];
}
