<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'patients';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'medical_record_number',
        'nik',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'phone',
        'address',
        'occupation',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'blood_type',
        'satusehat_ihs_number',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    /**
     * User who created the patient record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Riwayat kunjungan pasien.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class)->latest();
    }

    /**
     * Format gender label.
     */
    public function getGenderLabelAttribute(): string
    {
        return match ($this->gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => '-',
        };
    }

    /**
     * Calculate patient age from birth date.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->age : null;
    }

    /**
     * Generate an atomic, unique medical record number using number_sequences table.
     */
    public static function generateMedicalRecordNumber(): string
    {
        return DB::transaction(function () {
            $periodKey = date('Y');

            $sequence = DB::table('number_sequences')
                ->where('sequence_name', 'medical_record')
                ->where('period_key', $periodKey)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                $nextNumber = 1;
                DB::table('number_sequences')->insert([
                    'sequence_name' => 'medical_record',
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

            return sprintf('RM-%s-%05d', $periodKey, $nextNumber);
        });
    }
}
