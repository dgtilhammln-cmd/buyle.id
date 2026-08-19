{{--
    Partial: _key_cell.blade.php
    Usage: @include('admin.apikeys._key_cell', ['keyVal' => 'xxxx', 'active' => true/false])
    $active = true  → border hijau/kuning (key yang sedang dipakai)
    $active = false → border abu-abu
--}}
@php
    $hidden   = $keyVal ? substr($keyVal, 0, 5) . str_repeat('•', 20) : 'Belum diatur';
    $hasKey   = !empty($keyVal);
    $border   = $active ? ($keyVal ? '#16A34A' : '#D97706') : '#E2E8F0';
    $bg       = $active ? ($keyVal ? '#F0FDF4' : '#FFFBEB') : '#FAFAFA';
@endphp
<div style="background:{{ $bg }};border:1.5px solid {{ $border }};border-radius:8px;padding:.5rem .75rem;display:inline-flex;align-items:center;gap:.5rem;max-width:100%;">
    @if($active)
        <span style="width:8px;height:8px;border-radius:50%;background:{{ $hasKey ? '#16A34A' : '#D97706' }};flex-shrink:0;"></span>
    @endif
    <div class="api-key-hidden" style="color:{{ $hasKey ? '#DC2626' : '#94A3B8' }};">
        <button type="button" onclick="toggleApiKey(this)"
            data-full="{{ $keyVal }}"
            data-hidden="{{ $hidden }}"
            style="background:none;border:none;cursor:pointer;color:inherit;display:flex;align-items:center;padding:0;">
            <svg class="icon-hide" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/>
            </svg>
            <svg class="icon-show" style="display:none;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        </button>
        <span class="key-text" style="font-family:monospace;font-size:.82rem;letter-spacing:.5px;">{{ $hidden }}</span>
    </div>
    @if(!$hasKey)
        <span style="font-size:.75rem;color:#94A3B8;font-style:italic;">Belum diisi</span>
    @endif
</div>
