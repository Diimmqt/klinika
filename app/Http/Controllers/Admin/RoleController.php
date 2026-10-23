<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $type = $request->input('type', 'all');
        $usage = $request->input('usage', 'all');
        $sort = $request->input('sort', 'name_asc');

        $systemRoleNames = [
            'super_admin', 'admin_klinik', 'pendaftaran', 'perawat',
            'dokter', 'apoteker', 'kasir', 'manajemen'
        ];

        $query = Role::query()->withCount(['users', 'permissions']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($type === 'system') {
            $query->whereIn('name', $systemRoleNames);
        } elseif ($type === 'custom') {
            $query->whereNotIn('name', $systemRoleNames);
        }

        if ($usage === 'used') {
            $query->has('users');
        } elseif ($usage === 'unused') {
            $query->doesntHave('users');
        }

        match ($sort) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'latest' => $query->latest(),
            default => $query->orderBy('name', 'asc'),
        };

        $roles = $query->paginate(15)->withQueryString();

        $allRoles = Role::withCount('users')->get();
        $systemCount = $allRoles->filter(fn ($r) => in_array($r->name, $systemRoleNames))->count();
        $customCount = $allRoles->count() - $systemCount;
        $totalAssignments = $allRoles->sum('users_count');

        $stats = [
            'total' => $allRoles->count(),
            'system' => $systemCount,
            'custom' => $customCount,
            'assignments' => $totalAssignments,
        ];

        return view('admin.roles.index', compact('roles', 'stats', 'search', 'type', 'usage', 'sort', 'systemRoleNames'));
    }

    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function (Permission $p) {
            return explode('.', $p->name)[0] ?? 'other';
        });

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        try {
            $role = DB::transaction(function () use ($request) {
                $role = Role::create([
                    'name' => $request->input('name'),
                    'guard_name' => 'web',
                    'description' => $request->input('description'),
                ]);
                $submittedPermissions = $request->input('permissions', []);
                $validPermissions = Permission::whereIn('name', $submittedPermissions)->pluck('name')->toArray();
                $role->syncPermissions($validPermissions);

                return $role;
            });

            return redirect()
                ->route('admin.roles.show', $role)
                ->with('success', "Role '{$role->name}' berhasil dibuat.");
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal membuat role: '.$e->getMessage());
        }
    }

    public function show(Role $role): View
    {
        $role->load('permissions');
        $role->loadCount('users');

        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role): View
    {
        $role->load('permissions', 'users');
        $role->loadCount('users');

        $systemRoleNames = [
            'super_admin', 'admin_klinik', 'pendaftaran', 'perawat',
            'dokter', 'apoteker', 'kasir', 'manajemen',
        ];

        return view('admin.roles.edit', compact('role', 'systemRoleNames'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        try {
            $systemRoleNames = [
                'super_admin', 'admin_klinik', 'pendaftaran', 'perawat',
                'dokter', 'apoteker', 'kasir', 'manajemen',
            ];

            if (in_array($role->name, $systemRoleNames) && $request->input('name') !== $role->name) {
                return back()->withInput()->with('error', "Nama role sistem '{$role->name}' tidak boleh diubah.");
            }

            DB::transaction(function () use ($request, $role) {
                $role->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                ]);
                $submittedPermissions = $request->input('permissions', []);
                $validPermissions = Permission::whereIn('name', $submittedPermissions)->pluck('name')->toArray();
                $role->syncPermissions($validPermissions);
            });

            return redirect()
                ->route('admin.roles.show', $role)
                ->with('success', "Role '{$role->name}' berhasil diperbarui.");
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui role: '.$e->getMessage());
        }
    }

    public function destroy(Role $role): RedirectResponse
    {
        try {
            if ($role->name === 'super_admin') {
                return back()->with('error', 'Role super_admin tidak boleh dihapus.');
            }

            if ($role->users()->count() > 0) {
                return back()->with('error', 'Role masih dipakai user. Lepas assignment dulu.');
            }

            $name = $role->name;
            $role->delete();

            return redirect()
                ->route('admin.roles.index')
                ->with('success', "Role '{$name}' berhasil dihapus.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus role: '.$e->getMessage());
        }
    }
}
