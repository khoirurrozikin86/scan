<x-app-layout :title="'Manage User — ' . $user->name">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Manage: {{ $user->name }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-6">
        {{-- Roles --}}
        <div class="bg-white dark:bg-gray-800 rounded shadow p-4">
            <h3 class="font-semibold mb-3">Roles</h3>
            <form method="POST" action="{{ route('super.users.roles.sync', $user) }}">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 gap-2">
                    @foreach ($roles as $r)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="roles[]" value="{{ $r->name }}"
                                @checked($user->roles->pluck('name')->contains($r->name))>
                            <span>{{ $r->name }}</span>
                        </label>
                    @endforeach
                </div>
                <button class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded">Sync Roles</button>
            </form>
        </div>

        {{-- Direct Permissions --}}
        <div class="bg-white dark:bg-gray-800 rounded shadow p-4">
            <h3 class="font-semibold mb-3">Direct Permissions</h3>
            <form method="POST" action="{{ route('super.users.perms.sync', $user) }}">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 gap-2 h-64 overflow-auto pr-2">
                    @foreach ($permissions as $p)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="permissions[]" value="{{ $p->name }}"
                                @checked($user->permissions->pluck('name')->contains($p->name))>
                            <span>{{ $p->name }}</span>
                        </label>
                    @endforeach
                </div>
                <button class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded">Sync Permissions</button>
            </form>
        </div>
    </div>
</x-app-layout>
