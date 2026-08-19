@if ($paginator->hasPages())
<nav aria-label="Navigasi Halaman" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-top:2rem;">

    {{-- Info hasil --}}
    <div style="font-size:0.8125rem;color:#71717A;">
        Menampilkan
        <strong style="color:#D4D4D8;">{{ $paginator->firstItem() }}</strong>
        –
        <strong style="color:#D4D4D8;">{{ $paginator->lastItem() }}</strong>
        dari
        <strong style="color:#D4D4D8;">{{ $paginator->total() }}</strong>
        artikel
    </div>

    {{-- Tombol Halaman --}}
    <div style="display:flex;align-items:center;gap:0.375rem;">

        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.5rem 1rem;border:1px solid rgba(255,255,255,0.08);color:#3F3F46;border-radius:3px;font-size:0.8125rem;font-weight:600;cursor:not-allowed;user-select:none;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                Prev
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.5rem 1rem;border:1px solid rgba(255,255,255,0.12);color:#A1A1AA;border-radius:3px;font-size:0.8125rem;font-weight:600;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.borderColor='#F5A623';this.style.color='#F5A623';" onmouseout="this.style.borderColor='rgba(255,255,255,0.12)';this.style.color='#A1A1AA';">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                Prev
            </a>
        @endif

        {{-- Nomor Halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="display:inline-flex;align-items:center;justify-content:center;width:2rem;height:2rem;color:#3F3F46;font-size:0.8125rem;">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:2rem;height:2rem;background:#F5A623;color:#000;border-radius:3px;font-size:0.8125rem;font-weight:700;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;width:2rem;height:2rem;border:1px solid rgba(255,255,255,0.08);color:#71717A;border-radius:3px;font-size:0.8125rem;font-weight:600;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.borderColor='#F5A623';this.style.color='#F5A623';" onmouseout="this.style.borderColor='rgba(255,255,255,0.08)';this.style.color='#71717A';">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.5rem 1rem;border:1px solid rgba(255,255,255,0.12);color:#A1A1AA;border-radius:3px;font-size:0.8125rem;font-weight:600;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.borderColor='#F5A623';this.style.color='#F5A623';" onmouseout="this.style.borderColor='rgba(255,255,255,0.12)';this.style.color='#A1A1AA';">
                Next
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        @else
            <span style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.5rem 1rem;border:1px solid rgba(255,255,255,0.08);color:#3F3F46;border-radius:3px;font-size:0.8125rem;font-weight:600;cursor:not-allowed;user-select:none;">
                Next
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
        @endif

    </div>
</nav>
@endif
