<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PatientController extends Controller
{
    /**
     * Display a listing of patients with search and filters.
     */
    public function index(Request $request): View
    {
        abort_unless(auth()->user()?->can('patient.view'), 403, 'Akses ditolak: Anda tidak memiliki izin melihat data pasien.');

        $search = trim((string) $request->input('search'));
        $gender = $request->input('gender');
        $sort = $request->input('sort', 'latest');

        $query = Patient::query();

        // Pencarian multi-field sesuai kolom yang ada di tabel patients
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('medical_record_number', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if (in_array($gender, ['L', 'P'], true)) {
            $query->where('gender', $gender);
        }

        match ($sort) {
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->latest(),
        };

        $patients = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => Patient::count(),
            'this_month' => Patient::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'male' => Patient::where('gender', 'L')->count(),
            'female' => Patient::where('gender', 'P')->count(),
        ];

        return view('patients.index', compact('patients', 'stats', 'search', 'gender', 'sort'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create(): View
    {
        abort_unless(auth()->user()?->can('patient.create'), 403, 'Akses ditolak: Anda tidak memiliki izin mendaftarkan pasien.');
        return view('patients.create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(StorePatientRequest $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('patient.create'), 403, 'Akses ditolak: Anda tidak memiliki izin mendaftarkan pasien.');
        try {
            $patient = DB::transaction(function () use ($request) {
                $medicalRecordNumber = Patient::generateMedicalRecordNumber();

                // Gunakan id pengguna login, atau fallback akun sistem default saat auth belum dipasang
                $createdBy = auth()->id();
                if (! $createdBy) {
                    $defaultUser = User::firstOrCreate(
                        ['email' => 'admin@klinika.id'],
                        [
                            'name' => 'Petugas Pendaftaran',
                            'password' => bcrypt('password'),
                        ]
                    );
                    $createdBy = $defaultUser->id;
                }

                return Patient::create(array_merge($request->validated(), [
                    'medical_record_number' => $medicalRecordNumber,
                    'created_by' => $createdBy,
                ]));
            });

            return redirect()
                ->route('patients.show', $patient)
                ->with('success', "Pasien '{$patient->name}' berhasil didaftarkan dengan No. RM {$patient->medical_record_number}.");
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mendaftarkan pasien: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified patient details.
     */
    public function show(Patient $patient): View
    {
        abort_unless(auth()->user()?->can('patient.view'), 403, 'Akses ditolak: Anda tidak memiliki izin melihat data pasien.');
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient): View
    {
        abort_unless(auth()->user()?->can('patient.update'), 403, 'Akses ditolak: Anda tidak memiliki izin mengubah data pasien.');
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        abort_unless(auth()->user()?->can('patient.update'), 403, 'Akses ditolak: Anda tidak memiliki izin mengubah data pasien.');
        try {
            $patient->update($request->validated());

            return redirect()
                ->route('patients.show', $patient)
                ->with('success', "Data pasien '{$patient->name}' berhasil diperbarui.");
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data pasien: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified patient from storage (Soft Delete).
     */
    public function destroy(Patient $patient): RedirectResponse
    {
        abort_unless(auth()->user()?->can('patient.delete'), 403, 'Akses ditolak: Anda tidak memiliki izin menghapus data pasien.');
        try {
            $name = $patient->name;
            $mrn = $patient->medical_record_number;
            $patient->delete();

            return redirect()
                ->route('patients.index')
                ->with('success', "Data pasien {$name} ({$mrn}) berhasil dihapus.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus data pasien: ' . $e->getMessage());
        }
    }
}
