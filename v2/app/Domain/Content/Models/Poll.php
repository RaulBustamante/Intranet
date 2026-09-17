<?php

namespace App\Domain\Content\Models;

use App\Domain\Content\Models\Concerns\HasLocalizedName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    use HasLocalizedName;

    protected $fillable = ['question_es', 'question_en', 'opens_at', 'closes_at', 'is_active', 'created_by'];

    protected function casts(): array
    {
        return ['opens_at' => 'datetime', 'closes_at' => 'datetime', 'is_active' => 'boolean'];
    }

    public function options(): HasMany { return $this->hasMany(PollOption::class); }
    public function votes(): HasMany   { return $this->hasMany(PollVote::class); }

    public function getQuestionAttribute(): string { return $this->localized('question'); }

    public function hasVoted(int $employeeId): bool
    {
        return $this->votes()->where('employee_id', $employeeId)->exists();
    }

    public function totalVotes(): int
    {
        return $this->votes()->count();
    }
}
