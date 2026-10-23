@php
    $selected = old('permissions', isset($role) ? $role->permissions->pluck('name')->toArray() : []);
@endphp

<div>
    <h3 class="text-xs font-bold uppercase tracking-wider klinika-muted border-b pb-2 mb-3" style="border-color:var(--klinika-border)">
        Permission Role
    </h3>
    <div class="space-y-4">
        @foreach ($permissions as $module => $items)
            <div>
                <div class="text-xs font-semibold mb-2 capitalize" style="color:var(--klinika-text)">{{ $module }}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach ($items as $permission)
                        <label class="flex items-start gap-2 p-2.5 rounded-md border cursor-pointer" style="border-color:var(--klinika-border)">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                   {{ in_array($permission->name, $selected) ? 'checked' : '' }}
                                   class="mt-0.5" style="accent-color:var(--klinika-primary)">
                            <span>
                                <span class="text-xs font-mono font-medium block" style="color:var(--klinika-text)">{{ $permission->name }}</span>
                                @if ($permission->description)
                                    <span class="text-[11px] klinika-muted">{{ $permission->description }}</span>
                                @endif
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
