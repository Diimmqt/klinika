<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Queue extends Model
{
    use HasFactory;

    protected $table = 'queues';

    protected $fillable = [
        'visit_id',
        'poli_id',
        'queue_date',
        'queue_number',
        'queue_code',
        'status',
        'called_at',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'queue_date' => 'date',
            'queue_number' => 'integer',
            'called_at' => 'datetime',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(QueueStatusLog::class)->orderBy('changed_at', 'asc');
    }

    /**
     * Label status bahasa Indonesia.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'dipanggil' => 'Dipanggil',
            'sedang_dilayani' => 'Sedang Dilayani',
            'selesai' => 'Selesai',
            default => ucfirst($this->status),
        };
    }

    /**
     * Generate nomor antrean secara atomic untuk kunjungan tertentu.
     * Menggunakan tabel queue_counters dan lockForUpdate() agar aman dari duplikasi.
     */
    public static function createForVisit(Visit $visit): self
    {
        return DB::transaction(function () use ($visit) {
            $poli = $visit->poli ?? Poli::findOrFail($visit->poli_id);
            $queueDate = $visit->visit_date ? $visit->visit_date->format('Y-m-d') : date('Y-m-d');

            // Lock baris counter poli + tanggal
            $counter = DB::table('queue_counters')
                ->where('poli_id', $poli->id)
                ->where('queue_date', $queueDate)
                ->lockForUpdate()
                ->first();

            if (! $counter) {
                $nextNumber = 1;
                DB::table('queue_counters')->insert([
                    'poli_id' => $poli->id,
                    'queue_date' => $queueDate,
                    'last_number' => $nextNumber,
                    'updated_at' => now(),
                ]);
            } else {
                $nextNumber = $counter->last_number + 1;
                DB::table('queue_counters')
                    ->where('id', $counter->id)
                    ->update([
                        'last_number' => $nextNumber,
                        'updated_at' => now(),
                    ]);
            }

            $prefix = $poli->queue_prefix ?: 'A';
            $queueCode = sprintf('%s-%03d', strtoupper($prefix), $nextNumber);

            $queue = self::create([
                'visit_id' => $visit->id,
                'poli_id' => $poli->id,
                'queue_date' => $queueDate,
                'queue_number' => $nextNumber,
                'queue_code' => $queueCode,
                'status' => 'menunggu',
            ]);

            QueueStatusLog::create([
                'queue_id' => $queue->id,
                'from_status' => null,
                'to_status' => 'menunggu',
                'changed_by' => $visit->created_by,
                'changed_at' => now(),
            ]);

            return $queue;
        });
    }

    /**
     * Update status antrean serta mencatat timestamp dan riwayat log status.
     */
    public function updateStatus(string $newStatus, ?int $userId = null): void
    {
        $allowed = ['menunggu', 'dipanggil', 'sedang_dilayani', 'selesai'];
        if (! in_array($newStatus, $allowed, true)) {
            throw new \InvalidArgumentException("Status antrean tidak valid: {$newStatus}");
        }

        DB::transaction(function () use ($newStatus, $userId) {
            $fromStatus = $this->status;
            $updates = ['status' => $newStatus];

            if ($newStatus === 'dipanggil' && ! $this->called_at) {
                $updates['called_at'] = now();
            } elseif ($newStatus === 'sedang_dilayani' && ! $this->started_at) {
                $updates['started_at'] = now();
                // Update status kunjungan terkait menjadi in_progress
                if ($this->visit && $this->visit->status === 'registered') {
                    $this->visit->update(['status' => 'in_progress']);
                }
            } elseif ($newStatus === 'selesai' && ! $this->finished_at) {
                $updates['finished_at'] = now();
                // Update status kunjungan terkait menjadi completed
                if ($this->visit && $this->visit->status !== 'completed') {
                    $this->visit->update(['status' => 'completed']);
                }
            }

            $this->update($updates);

            QueueStatusLog::create([
                'queue_id' => $this->id,
                'from_status' => $fromStatus,
                'to_status' => $newStatus,
                'changed_by' => $userId ?: auth()->id(),
                'changed_at' => now(),
            ]);
        });
    }
}
