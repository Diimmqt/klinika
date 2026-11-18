<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poli extends Model
{
    use HasFactory;

    protected $table = 'polis';

    protected $fillable = [
        'code',
        'name',
        'queue_prefix',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function practitioners(): BelongsToMany
    {
        return $this->belongsToMany(Practitioner::class, 'schedules')
            ->withPivot(['id', 'day_of_week', 'start_time', 'end_time', 'quota', 'is_active'])
            ->withTimestamps();
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }
}
