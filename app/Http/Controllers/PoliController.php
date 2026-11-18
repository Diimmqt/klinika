<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePoliRequest;
use App\Models\Poli;
use App\Models\Practitioner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PoliController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', '');

        $query = Poli::query()->withCount([
            'visits',
            'practitioners as practitioners_count' => fn ($q) => $q->select(DB::raw('count(distinct(practitioners.id))')),
        ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('queue_prefix', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $polis = $query->orderBy('name')->paginate(10)->withQueryString();

        $stats = [
            'total' => Poli::count(),
            'active' => Poli::where('is_active', true)->count(),
            'inactive' => Poli::where('is_active', false)->count(),
            'practitioners' => Practitioner::whereHas('polis')->count(),
        ];

        return view('polis.index', compact('polis', 'stats', 'search', 'status'));
    }

    public function create(): View
    {
        return view('polis.create');
    }

    public function store(StorePoliRequest $request): RedirectResponse
    {
        $poli = Poli::create($request->validated());

        return redirect()
            ->route('polis.show', $poli)
            ->with('success', "Data poli '{$poli->name}' berhasil ditambahkan.");
    }

    public function show(Poli $poli): View
    {
        $poli->load([
            'practitioners' => fn ($q) => $q->orderBy('practitioner_type')->orderBy('name'),
        ])->loadCount(['visits', 'queues']);

        $connectedPractitioners = $poli->practitioners->unique('id')->values();

        return view('polis.show', compact('poli', 'connectedPractitioners'));
    }

    public function edit(Poli $poli): View
    {
        return view('polis.edit', compact('poli'));
    }

    public function update(StorePoliRequest $request, Poli $poli): RedirectResponse
    {
        $poli->update($request->validated());

        return redirect()
            ->route('polis.show', $poli)
            ->with('success', "Data poli '{$poli->name}' berhasil diperbarui.");
    }

    public function toggleStatus(Poli $poli): RedirectResponse
    {
        $poli->update(['is_active' => ! $poli->is_active]);
        $label = $poli->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Poli '{$poli->name}' berhasil {$label}.");
    }

    public function destroy(Poli $poli): RedirectResponse
    {
        if ($poli->visits()->exists() || $poli->queues()->exists()) {
            return back()->with(
                'error',
                "Poli '{$poli->name}' sudah memiliki data kunjungan atau antrean. Silakan nonaktifkan poli alih-alih menghapusnya."
            );
        }

        $name = $poli->name;
        $poli->delete();

        return redirect()
            ->route('polis.index')
            ->with('success', "Data poli '{$name}' berhasil dihapus.");
    }
}

