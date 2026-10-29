<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueCounter extends Model
{
    use HasFactory;

    protected $table = 'queue_counters';

    public $timestamps = false;

    protected $fillable = [
        'poli_id',
        'queue_date',
        'last_number',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'queue_date' => 'date',
            'last_number' => 'integer',
        ];
    }

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class);
    }
}
