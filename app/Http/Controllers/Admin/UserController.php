<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $role = $request->input('role');
        $status = $request->input('status');
        $sort = $request->input('sort', 'latest');

        $query = User::query()->with('roles');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->role($role);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        match ($sort) {
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->latest(),
        };

        $users = $query->paginate(8)->withQueryString();
        $roles = Role::orderBy('name')->get();

        $stats = [
            'total'       => User::count(),
            'active'      => User::where('is_active', true)->count(),
            'inactive'    => User::where('is_active', false)->count(),
            'super_admin' => User::role('super_admin')->count(),
            'roles'       => Role::count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'stats', 'search', 'role', 'status', 'sort'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $user = DB::transaction(function () use ($request) {
                // Undangan: admin tidak set password. Placeholder acak agar kolom NOT NULL
                // terisi; login diblokir lewat is_active=false sampai aktivasi.
                $user = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Str::password(64),
                    'is_active' => false,
                ]);
                $user->syncRoles($request->input('roles', []));

                return $user->load('roles');
            });

            $sendInvitation = $request->boolean('send_invitation');
            $expires = now()->addDay()->timezone(config('app.timezone'))->format('d M Y H:i');

            $message = $sendInvitation
                ? "Akun '{$user->name}' dibuat. Undangan aktivasi siap dikirim; pengguna belum dapat login."
                : "Akun '{$user->name}' dibuat dengan status menunggu aktivasi (undangan belum dikirim).";

            return redirect()
                ->route('admin.users.create')
                ->with('success', $message)
                ->with('created_user', [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->pluck('name')->join(', ') ?: '—',
                    'expires' => $expires,
                ]);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal membuat user: '.$e->getMessage());
        }
    }

    public function show(User $user): View
    {
        $user->load('roles', 'permissions');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get();
        $user->load('roles');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $user) {
                $data = $request->safe()->except(['roles', 'password', 'password_confirmation', 'audit_note']);
                $data['is_active'] = $request->boolean('is_active');

                if ($request->filled('password')) {
                    $data['password'] = $request->input('password');
                }

                // shortcut: jangan nonaktifkan satu-satunya super_admin aktif
                if (! $data['is_active'] && $user->hasRole('super_admin')) {
                    $activeSuperAdmins = User::role('super_admin')->where('is_active', true)->where('id', '!=', $user->id)->count();
                    if ($activeSuperAdmins === 0) {
                        throw new \RuntimeException('Tidak dapat menonaktifkan satu-satunya Super Admin aktif.');
                    }
                }

                $user->update($data);
                $user->syncRoles($request->input('roles', []));
            });

            return redirect()
                ->route('admin.users.show', $user)
                ->with('success', "User '{$user->name}' berhasil diperbarui.");
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui user: '.$e->getMessage());
        }
    }

    public function deactivate(User $user): View
    {
        $user->load('roles');

        $activeSuperAdminCount = User::role('super_admin')->where('is_active', true)->count();
        $isLastSuperAdmin = $user->hasRole('super_admin') && $activeSuperAdminCount <= 1;
        $isSelf = $user->id === auth()->id();

        return view('admin.users.deactivate', compact('user', 'isLastSuperAdmin', 'isSelf'));
    }

    public function showResetPassword(User $user): View
    {
        $user->load('roles');

        return view('admin.users.reset-password', compact('user'));
    }

    public function sendResetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'verified_identity' => ['required'],
        ]);

        return back()
            ->with('reset_sent', true)
            ->with('sent_at', now());
    }

    public function destroy(User $user): RedirectResponse
    {
        try {
            if ($user->id === auth()->id()) {
                return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            }

            if ($user->hasRole('super_admin')) {
                $activeSuperAdmins = User::role('super_admin')->where('is_active', true)->where('id', '!=', $user->id)->count();
                if ($activeSuperAdmins === 0) {
                    return back()->with('error', 'Tidak dapat menonaktifkan satu-satunya Super Admin aktif.');
                }
            }

            $name = $user->name;
            $user->update(['is_active' => false]);

            return redirect()
                ->route('admin.users.index')
                ->with('success', "User '{$name}' berhasil dinonaktifkan.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menonaktifkan user: '.$e->getMessage());
        }
    }
}
