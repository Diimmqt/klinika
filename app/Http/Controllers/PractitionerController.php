<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePractitionerRequest;
use App\Models\Poli;
use App\Models\Practitioner;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PractitionerController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $profession = (string) $request->query('profession', '');
        $poliId = $request->query('poli_id');
        $status = (string) $request->query('status', '');

        $query = Practitioner::query()->with(['polis', 'user']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('str_number', 'like', "%{$search}%")
                    ->orWhere('sip_number', 'like', "%{$search}%")
                    ->orWhere('satusehat_practitioner_id', 'like', "%{$search}%");
            });
        }

        if ($profession !== '' && array_key_exists($profession, Practitioner::PROFESSIONS)) {
            $query->where('practitioner_type', $profession);
        }

        if ($poliId !== null && $poliId !== '') {
            $query->whereHas('polis', fn ($q) => $q->where('polis.id', (int) $poliId));
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $practitioners = $query
            ->orderBy('practitioner_type')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $professionCounts = Practitioner::query()
            ->selectRaw('practitioner_type, COUNT(*) as total')
            ->groupBy('practitioner_type')
            ->pluck('total', 'practitioner_type');

        $stats = [
            'total' => Practitioner::count(),
            'active' => Practitioner::where('is_active', true)->count(),
            'dokter' => (int) ($professionCounts['dokter'] ?? 0),
            'perawat' => (int) ($professionCounts['perawat'] ?? 0),
            'apoteker' => (int) ($professionCounts['apoteker'] ?? 0),
        ];

        $polis = Poli::orderBy('name')->get(['id', 'code', 'name', 'is_active']);
        $professions = Practitioner::PROFESSIONS;

        return view('practitioners.index', compact(
            'practitioners',
            'stats',
            'polis',
            'professions',
            'search',
            'profession',
            'poliId',
            'status'
        ));
    }

    public function create(Request $request): View
    {
        $polis = Poli::where('is_active', true)->orderBy('name')->get();
        $selectedPoliIds = $request->filled('poli_id') ? [(int) $request->input('poli_id')] : [];
        $professions = Practitioner::PROFESSIONS;
        $users = $this->availableUsersForLinking();

        return view('practitioners.create', compact('polis', 'selectedPoliIds', 'professions', 'users'));
    }

    public function store(StorePractitionerRequest $request): RedirectResponse
    {
        $practitioner = DB::transaction(function () use ($request) {
            $practitioner = Practitioner::create($request->safe()->except('poli_ids'));
            $practitioner->syncPoliAssignments($request->input('poli_ids', []));

            return $practitioner;
        });

        return redirect()
            ->route('practitioners.show', $practitioner)
            ->with('success', "Data tenaga kesehatan '{$practitioner->name}' berhasil ditambahkan.");
    }

    public function show(Practitioner $practitioner): View
    {
        $practitioner->load(['polis', 'user'])->loadCount('visits');
        $connectedPolis = $practitioner->polis->unique('id')->values();

        return view('practitioners.show', compact('practitioner', 'connectedPolis'));
    }

    public function edit(Practitioner $practitioner): View
    {
        $selectedPoliIds = $practitioner->polis()
            ->pluck('polis.id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $polis = Poli::query()
            ->where('is_active', true)
            ->when($selectedPoliIds !== [], fn ($q) => $q->orWhereIn('id', $selectedPoliIds))
            ->orderBy('name')
            ->get();

        $professions = Practitioner::PROFESSIONS;
        $users = $this->availableUsersForLinking($practitioner->user_id);

        return view('practitioners.edit', compact(
            'practitioner',
            'polis',
            'selectedPoliIds',
            'professions',
            'users'
        ));
    }

    public function update(StorePractitionerRequest $request, Practitioner $practitioner): RedirectResponse
    {
        DB::transaction(function () use ($request, $practitioner) {
            $practitioner->update($request->safe()->except('poli_ids'));
            $practitioner->syncPoliAssignments($request->input('poli_ids', []));
        });

        return redirect()
            ->route('practitioners.show', $practitioner)
            ->with('success', "Data tenaga kesehatan '{$practitioner->name}' berhasil diperbarui.");
    }

    public function toggleStatus(Practitioner $practitioner): RedirectResponse
    {
        $practitioner->update(['is_active' => ! $practitioner->is_active]);
        $label = $practitioner->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Tenaga kesehatan '{$practitioner->name}' berhasil {$label}.");
    }

    public function destroy(Practitioner $practitioner): RedirectResponse
    {
        if ($practitioner->visits()->exists()) {
            return back()->with(
                'error',
                "Tenaga kesehatan '{$practitioner->name}' sudah terhubung ke riwayat kunjungan. Silakan nonaktifkan statusnya alih-alih menghapus data."
            );
        }

        $name = $practitioner->name;
        $practitioner->delete();

        return redirect()
            ->route('practitioners.index')
            ->with('success', "Data tenaga kesehatan '{$name}' berhasil dihapus.");
    }

    private function availableUsersForLinking(?int $currentUserId = null)
    {
        $takenUserIds = Practitioner::query()
            ->whereNotNull('user_id')
            ->when($currentUserId, fn ($q) => $q->where('user_id', '!=', $currentUserId))
            ->pluck('user_id');

        return User::query()
            ->whereNotIn('id', $takenUserIds)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }
}

