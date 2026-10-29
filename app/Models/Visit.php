<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Visit extends Model
{
    use HasFactory;

    protected $table = 'visits';

    protected $fillable = [
        'visit_number',
        'patient_id',
        'poli_id',
        'practitioner_id',
        'visit_date',
        'status',
        'complaint',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class);
    }

    public function queue(): HasOne
    {
        return $this->hasOne(Queue::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    /**
     * Decode complaint payload, supporting both legacy raw text and structured clinical JSON.
     */
    public function getClinicalPayloadAttribute(): array
    {
        if (empty($this->complaint)) {
            return [];
        }

        $decoded = json_decode($this->complaint, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return ['keluhan_utama' => $this->complaint];
    }

    /**
     * Data pemeriksaan awal dan tanda vital perawat.
     */
    public function getAssessmentDataAttribute(): array
    {
        $payload = $this->clinical_payload;

        // Mendukung format payload baru (bersarang) atau format perawat sebelumnya
        if (isset($payload['nurse_assessment']) && is_array($payload['nurse_assessment'])) {
            return $payload['nurse_assessment'];
        }

        return $payload;
    }

    /**
     * Data pemeriksaan medis dokter (SOAP, Diagnosis, Tindakan, Resep).
     */
    public function getDoctorExaminationAttribute(): array
    {
        $payload = $this->clinical_payload;

        return $payload['doctor_examination'] ?? [];
    }

    /**
     * Catatan SOAP dokter.
     */
    public function getSoapAttribute(): array
    {
        return $this->doctor_examination['soap'] ?? [
            'subjective' => '',
            'objective' => '',
            'assessment' => '',
            'plan' => '',
        ];
    }

    /**
     * Daftar diagnosis dokter (kode ICD-10).
     */
    public function getDiagnosesAttribute(): array
    {
        return $this->doctor_examination['diagnosis'] ?? [];
    }

    /**
     * Tindakan medis dokter.
     */
    public function getActionsAttribute(): array
    {
        return $this->doctor_examination['actions'] ?? [];
    }

    /**
     * Resep obat dokter.
     */
    public function getPrescriptionsAttribute(): array
    {
        return $this->doctor_examination['prescriptions'] ?? [];
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'registered' => 'Terdaftar',
            'in_progress' => 'Sedang Dilayani',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    /**
     * Generate nomor kunjungan atomic menggunakan tabel number_sequences.
     */
    public static function generateVisitNumber(): string
    {
        return DB::transaction(function () {
            $periodKey = date('Ymd');

            $sequence = DB::table('number_sequences')
                ->where('sequence_name', 'visit')
                ->where('period_key', $periodKey)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                $nextNumber = 1;
                DB::table('number_sequences')->insert([
                    'sequence_name' => 'visit',
                    'period_key' => $periodKey,
                    'last_number' => $nextNumber,
                    'updated_at' => now(),
                ]);
            } else {
                $nextNumber = $sequence->last_number + 1;
                DB::table('number_sequences')
                    ->where('id', $sequence->id)
                    ->update([
                        'last_number' => $nextNumber,
                        'updated_at' => now(),
                    ]);
            }

            return sprintf('VISIT-%s-%04d', $periodKey, $nextNumber);
        });
    }
}
