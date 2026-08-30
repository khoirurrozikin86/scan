<div class="dropdown text-center">
    <button class="btn btn-sm bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false"
        title="Actions">
        <i data-feather="more-vertical" class="icon-sm"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end">
        @foreach ($actions as $act)
            @php
                // meta keys yang tidak ikut ke payload
                $reserved = [
                    'type',
                    'label',
                    'icon',
                    'class',
                    'url',
                    'update_url',
                    'confirm',
                    'method',
                    'payload',
                    'payload_src',
                    'disabled',
                ];

                // jika controller sudah kasih 'payload', pakai itu;
                // kalau tidak, ambil semua key non-meta sebagai payload otomatis (backward compatible)
                $rawPayload = $act['payload'] ?? array_diff_key($act, array_flip($reserved));

                // normalisasi supaya aman di-json-kan
                $payload = [];
                foreach ($rawPayload as $k => $v) {
                    if ($v instanceof \Illuminate\Support\Collection) {
                        $v = $v->values()->all();
                    }
                    if (is_object($v)) {
                        if (method_exists($v, 'getKey')) {
                            $v = $v->getKey();
                        }
                        // Eloquent model → id
                        elseif (method_exists($v, '__toString')) {
                            $v = (string) $v;
                        }
                        // object stringable
                        else {
                            continue;
                        } // skip object lain
                    }
                    if (is_scalar($v) || is_array($v)) {
                        $payload[$k] = $v;
                    }
                }
            @endphp

            @if (($act['type'] ?? null) === 'edit')
                <li>
                    <button type="button"
                        class="dropdown-item d-flex align-items-center gap-2 {{ $act['class'] ?? 'btn-edit-role' }} js-action"
                        data-action="edit" data-update-url="{{ $act['update_url'] }}" {{-- atribut lama tetap ada sebagai fallback --}}
                        data-name="{{ $act['name'] ?? '' }}" data-group-name="{{ $act['group_name'] ?? '' }}"
                        {{-- payload baru (JSON) --}} data-payload='@json($payload)'>
                        <i data-feather="{{ $act['icon'] ?? 'edit-2' }}" class="icon-sm"></i>
                        {{ $act['label'] ?? 'Edit' }}
                    </button>
                </li>
            @elseif (($act['type'] ?? null) === 'link')
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 {{ $act['class'] ?? '' }}"
                        href="{{ $act['url'] }}">
                        @if (!empty($act['icon']))
                            <i data-feather="{{ $act['icon'] }}" class="icon-sm"></i>
                        @endif
                        {{ $act['label'] }}
                    </a>
                </li>
            @elseif (($act['type'] ?? null) === 'delete')
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <button type="button"
                        class="dropdown-item d-flex align-items-center gap-2 text-danger {{ $act['class'] ?? 'btn-delete-role' }} js-action"
                        data-action="delete" data-url="{{ $act['url'] }}"
                        data-confirm="{{ $act['confirm'] ?? 'Are you sure?' }}"
                        data-payload='@json($payload)' {{ !empty($act['disabled']) ? 'disabled' : '' }}>
                        <i data-feather="{{ $act['icon'] ?? 'trash-2' }}" class="icon-sm"></i>
                        {{ $act['label'] ?? 'Delete' }}
                    </button>
                </li>
            @endif
        @endforeach
    </ul>
</div>
