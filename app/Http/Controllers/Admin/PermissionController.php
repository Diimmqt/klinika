<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $sort = $request->input('sort', 'name_asc');

        $query = Permission::query()->withCount('roles');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        match ($sort) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'latest' => $query->latest(),
            default => $query->orderBy('name', 'asc'),
        };

        $permissions = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Permission::count(),
            'modules' => Permission::all()
                ->map(fn (Permission $p) => explode('.', $p->name)[0] ?? 'other')
                ->unique()
                ->count(),
        ];

        return view('admin.permissions.index', compact('permissions', 'stats', 'search', 'sort'));
    }

    public function create(): View
    {
        return view('admin.permissions.create');
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        try {
            $permission = Permission::create([
                'name' => $request->input('name'),
                'guard_name' => 'web',
                'description' => $request->input('description'),
            ]);

            return redirect()
                ->route('admin.permissions.index')
                ->with('success', "Permission '{$permission->name}' berhasil dibuat.");
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal membuat permission: '.$e->getMessage());
        }
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        try {
            $permission->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);

            return redirect()
                ->route('admin.permissions.index')
                ->with('success', "Permission '{$permission->name}' berhasil diperbarui.");
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui permission: '.$e->getMessage());
        }
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        try {
            if ($permission->roles()->count() > 0) {
                return back()->with('error', 'Permission masih terikat ke role. Lepas dulu dari role.');
            }

            $name = $permission->name;
            $permission->delete();

            return redirect()
                ->route('admin.permissions.index')
                ->with('success', "Permission '{$name}' berhasil dihapus.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus permission: '.$e->getMessage());
        }
    }
}
