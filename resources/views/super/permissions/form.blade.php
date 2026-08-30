<x-app-layout :title="$permission->exists ? 'Edit Permission' : 'New Permission'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">{{ $permission->exists ? 'Edit' : 'New' }}
            Permission</h2>
    </x-slot>

    <div class="max-w-xl mx-auto">
        @if ($errors->any())
            <div class="p-3 bg-red-100 text-red-700 rounded mb-3">{{ $errors->first() }}</div>
        @endif
        @if (session('ok'))
            <div class="p-3 bg-green-100 text-green-800 rounded mb-3">{{ session('ok') }}</div>
        @endif

        <form method="POST"
            action="{{ $permission->exists ? route('super.permissions.update', $permission) : route('super.permissions.store') }}">
            @csrf @if ($permission->exists)
                @method('PUT')
            @endif
            <label class="block mb-2 text-sm">Name</label>
            <input name="name" value="{{ old('name', $permission->name) }}" class="border rounded px-3 py-2 w-full"
                required>
            <div class="mt-4">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
