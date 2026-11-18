<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Practitioner extends Model
{
    use HasFactory;

    public const PROFESSIONS = [
        'dokter' => 'Dokter',
        'perawat' => 'Perawat',
        'apoteker' => 'Apoteker',
    ];

    protected $table = 'practitioners';

    protected $fillable = [
        'user_id',
        'name',
        'practitioner_type',
        'str_number',
        'sip_number',
        'satusehat_practitioner_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function polis(): BelongsToMany
    {
        return $this->belongsToMany(Poli::class, 'schedules')
            ->withPivot(['id', 'day_of_week', 'start_time', 'end_time', 'quota', 'is_active'])
            ->withTimestamps();
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function getProfessionLabelAttribute(): string
    {
        return self::PROFESSIONS[$this->practitioner_type] ?? ucfirst((string) $this->practitioner_type);
    }

    /**
     * Hubungkan tenaga kesehatan dengan poli melalui tabel schedules yang sudah ada
     * tanpa menambah tabel/kolom baru dan tanpa menimpa jadwal yang sudah ada.
     *
     * @param  array<int, int|string>  $poliIds
     */
    public function syncPoliAssignments(array $poliIds): void
    {
        $targetIds = collect($poliIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values();

        if ($targetIds->isEmpty()) {
            DB::table('schedules')->where('practitioner_id', $this->id)->delete();

            return;
        }

        DB::table('schedules')
            ->where('practitioner_id', $this->id)
            ->whereNotIn('poli_id', $targetIds->all())
            ->delete();

        $existingPoliIds = DB::table('schedules')
            ->where('practitioner_id', $this->id)
            ->pluck('poli_id')
            ->map(fn ($id) => (int) $id)
            ->unique();

        $now = now();
        foreach ($targetIds->diff($existingPoliIds) as $poliId) {
            DB::table('schedules')->insert([
                'practitioner_id' => $this->id,
                'poli_id' => $poliId,
                'day_of_week' => 1,
                'start_time' => '08:00:00',
                'end_time' => '14:00:00',
                'quota' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}

