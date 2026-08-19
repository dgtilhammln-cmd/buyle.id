@extends('layouts.admin')
@section('title', 'Hero Slides')
@section('page-title', 'Manajemen Banner')
@section('content')

@if(session('success'))
<div style="background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#6ee7b7;padding:.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:.875rem;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#fca5a5;padding:.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:.875rem;">
    {{ $errors->first() }}
</div>
@endif

<div class="admin-card" style="margin-bottom: 1.5rem;">
    <h3 style="font-size:.7rem;font-weight:700;color:#38BDF8;text-transform:uppercase;letter-spacing:.1em;margin:0 0 1rem;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.4rem;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
        Warna Background Oval (Hero Slider)
    </h3>
    <p style="font-size:.75rem;color:rgba(255,255,255,.35);margin:0 0 1rem;">Warna ini akan digunakan sebagai latar belakang oval di belakang hero slider. Anda bisa pilih warna solid atau isi manual dengan gradient CSS.</p>
    <form action="{{ route('admin.settings.update') }}" method="POST" style="display:flex;gap:.75rem;align-items:flex-end;flex-wrap:wrap;">
        @csrf
        <div style="flex:1;min-width:200px;">
            <label style="font-size:.7rem;font-weight:600;color:rgba(255,255,255,.5);display:block;margin-bottom:.5rem;">Warna / Gradient CSS</label>
            <div style="display:flex;gap:.5rem;align-items:center;">
                <input type="color" id="colorPicker" value="#1e3a8a" style="width:42px;height:42px;border-radius:8px;border:none;cursor:pointer;padding:2px;background:transparent;" title="Pilih warna solid" onchange="document.getElementById('gradientInput').value = this.value;">
                <input type="text" name="hero_oval_gradient" id="gradientInput" class="form-input" value="{{ \App\Models\Setting::get('hero_oval_gradient', 'linear-gradient(180deg, #1e3a8a 0%, #3b82f6 100%)') }}" placeholder="linear-gradient(180deg, #1e3a8a 0%, #3b82f6 100%)" style="flex:1;">
            </div>
            <p style="font-size:.68rem;color:rgba(255,255,255,.25);margin:.35rem 0 0;">Klik picker untuk warna solid, atau ketik manual untuk gradient. Contoh: <code style="color:#38BDF8;">linear-gradient(135deg, #1e3a8a, #3b82f6)</code></p>
        </div>
        <button type="submit" class="btn-primary" style="height:42px;padding:0 1.5rem;white-space:nowrap;flex-shrink:0;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-right:.35rem;"><polyline points="20 6 9 17 4 12"/></svg>
            Simpan Warna
        </button>
    </form>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <p style="font-size:.875rem;color:rgba(255,255,255,.45);margin:0;">Total Banner: {{ $slides->count() }}</p>
    <a href="{{ route('admin.hero_slides.create') }}" class="btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Banner
    </a>
</div>

@if($slides->isEmpty())
<div class="admin-card" style="text-align:center;padding:3rem;">
    <p style="color:rgba(255,255,255,.3);font-size:.9rem;">Belum ada slide. Klik "Tambah Slide" untuk membuat yang pertama.</p>
</div>
@else
<div style="display:flex;flex-direction:column;gap:1rem;">
    @foreach($slides as $slide)
    <div class="admin-card" style="display:flex;align-items:center;gap:1.5rem;">
        {{-- Thumbnail --}}
        <div style="width:100px;height:70px;border-radius:8px;overflow:hidden;flex-shrink:0;background:#1a1a1f;">
            @if($slide->image)
                <img src="{{ asset('storage/'.$slide->image) }}" style="width:100%;height:100%;object-fit:cover;" alt="{{ $slide->title }}">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.2);font-size:.7rem;">No Image</div>
            @endif
        </div>
        {{-- Info --}}
        <div style="flex:1;">
            <div style="font-size:.875rem;font-weight:600;color:#fff;margin-bottom:.25rem;">{{ $slide->title }}</div>
            <div style="font-size:.75rem;color:rgba(255,255,255,.4);line-height:1.4;">{{ Str::limit($slide->description, 80) }}</div>
            <div style="display:flex;gap:.75rem;margin-top:.5rem;align-items:center;">
                @if($slide->button_text)
                    <span style="font-size:.7rem;background:rgba(56,189,248,.1);color:#38BDF8;padding:.2rem .6rem;border-radius:4px;">{{ $slide->button_text }}</span>
                @endif
                @if($slide->button_url)
                    <span style="font-size:.7rem;color:rgba(255,255,255,.3);">→ {{ Str::limit($slide->button_url, 40) }}</span>
                @endif
            </div>
        </div>
        {{-- Order --}}
        <div style="text-align:center;flex-shrink:0;">
            <div style="font-size:.7rem;color:rgba(255,255,255,.3);margin-bottom:.25rem;">Urutan</div>
            <div style="font-size:1.25rem;font-weight:700;color:#fff;">{{ $slide->order }}</div>
        </div>
        {{-- Position --}}
        <div style="flex-shrink:0; width:130px; text-align:center;">
            <div style="font-size:.7rem;color:rgba(255,255,255,.3);margin-bottom:.35rem;">Posisi</div>
            @php
                $posLabel = match($slide->position ?? 'hero') {
                    'hero'    => ['label'=>'Hero Slider', 'icon'=>'<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>', 'color'=>'rgba(56,189,248,.15)', 'text'=>'#38BDF8'],
                    'utama'   => ['label'=>'Kanan Atas',  'icon'=>'<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>', 'color'=>'rgba(16,185,129,.15)',  'text'=>'#34d399'],
                    'samping' => ['label'=>'Kanan Bawah', 'icon'=>'<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="7" y1="7" x2="17" y2="17"></line><polyline points="17 7 17 17 7 17"></polyline></svg>', 'color'=>'rgba(245,158,11,.15)', 'text'=>'#fbbf24'],
                    default   => ['label'=>$slide->position,'icon'=>'<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>','color'=>'rgba(255,255,255,.07)','text'=>'#999'],
                };
            @endphp
            <span style="font-size:.7rem;padding:.3rem .6rem;border-radius:6px;background:{{ $posLabel['color'] }};color:{{ $posLabel['text'] }};font-weight:600;display:inline-flex;align-items:center;gap:.3rem;">
                {!! $posLabel['icon'] !!} {{ $posLabel['label'] }}
            </span>
        </div>
        {{-- Status --}}
        <div style="flex-shrink:0;">
            <span style="font-size:.7rem;padding:.3rem .75rem;border-radius:20px;{{ $slide->is_active ? 'background:rgba(16,185,129,.15);color:#34d399;' : 'background:rgba(255,255,255,.05);color:rgba(255,255,255,.3);' }}">
                {{ $slide->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
        {{-- Actions --}}
        <div style="display:flex;gap:.5rem;flex-shrink:0;">
            <a href="{{ route('admin.hero_slides.edit', $slide) }}" style="background:rgba(56,189,248,.1);color:#38BDF8;padding:.5rem .875rem;border-radius:6px;font-size:.8rem;text-decoration:none;transition:all .2s;">Edit</a>
            <form method="POST" action="{{ route('admin.hero_slides.destroy', $slide) }}" onsubmit="return confirm('Hapus slide ini?')">
                @csrf @method('DELETE')
                <button type="submit" style="background:rgba(239,68,68,.1);color:#f87171;padding:.5rem .875rem;border-radius:6px;font-size:.8rem;border:none;cursor:pointer;">Hapus</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
