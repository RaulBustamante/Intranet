<?php

namespace App\Domain\Content\Models;

use App\Domain\Content\Models\Concerns\HasLocalizedName;
use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    use HasLocalizedName;
    public $timestamps = false;
    protected $fillable = ['poll_id', 'label_es', 'label_en', 'sort_order'];

    public function getLabelAttribute(): string { return $this->localized('label'); }
}
