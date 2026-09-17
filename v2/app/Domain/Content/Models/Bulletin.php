<?php

namespace App\Domain\Content\Models;

use App\Domain\Content\Models\Concerns\HasLocalizedName;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Bulletin extends Model
{
    use HasLocalizedName;

    protected $fillable = ['title_es', 'title_en', 'period_year', 'period_month', 's3_key', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function getTitleAttribute(): string
    {
        return $this->localized('title');
    }

    public function periodLabel(): string
    {
        return CarbonImmutable::create($this->period_year, $this->period_month, 1)
            ->locale(app()->getLocale())->isoFormat('MMMM YYYY');
    }

    public function url(): string
    {
        return Storage::disk('s3')->url($this->s3_key);
    }
}
