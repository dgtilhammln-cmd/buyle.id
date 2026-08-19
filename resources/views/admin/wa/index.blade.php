@extends('layouts.admin')
@section('title','Pengaturan WhatsApp')
@section('page-title','Pengaturan WhatsApp')
@section('content')
<div style="max-width:900px;display:flex;flex-direction:column;gap:2rem;">

    {{-- Existing WA Numbers --}}
    <div class="admin-card">
        <h3 style="font-size:0.875rem;font-weight:700;color:#F5A623;text-transform:uppercase;margin:0 0 1.5rem;">Nomor WhatsApp Aktif</h3>
        <form method="POST" action="{{ route('admin.wa.update') }}">
            @csrf
            @forelse($waSettings as $wa)
            <input type="hidden" name="ids[]" value="{{ $wa->id }}">
            <div style="border:1px solid #27272A;padding:1.5rem;margin-bottom:1rem;background:#0A0A0A;">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:1rem;align-items:start;">
                    <div>
                        <label class="form-label">Label</label>
                        <input type="text" name="label[{{ $wa->id }}]" value="{{ $wa->label }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Nomor WA (tanpa +)</label>
                        <input type="text" name="nomor_wa[{{ $wa->id }}]" value="{{ $wa->nomor_wa }}" class="form-input" placeholder="6281331148731">
                    </div>
                    <div>
                        <label class="form-label">Urutan</label>
                        <input type="number" name="order[{{ $wa->id }}]" value="{{ $wa->order }}" class="form-input" min="0" style="width:70px;">
                    </div>
                    <div>
                        <label class="form-label">Hapus</label>
                        <form method="POST" action="{{ route('admin.wa.destroy', $wa->id) }}" onsubmit="return confirm('Hapus nomor ini?')" style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#f87171;padding:0.5rem 0.75rem;cursor:pointer;font-size:0.8125rem;font-weight:600;"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" title="Hapus"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></button>
                        </form>
                    </div>
                </div>
                <div style="margin-top:1rem;">
                    <label class="form-label">Template Pesan</label>
                    <textarea name="template_pesan[{{ $wa->id }}]" class="form-input" rows="3">{{ $wa->template_pesan }}</textarea>
                </div>
                <div style="display:flex;gap:2rem;margin-top:0.875rem;">
                    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.875rem;color:#D4D4D8;">
                        <input type="hidden" name="is_active[{{ $wa->id }}]" value="0">
                        <input type="checkbox" name="is_active[{{ $wa->id }}]" value="1" {{ $wa->is_active?'checked':'' }} style="accent-color:#F5A623;">
                        Aktif
                    </label>
                    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.875rem;color:#D4D4D8;">
                        <input type="radio" name="primary" value="{{ $wa->id }}" {{ $wa->is_primary?'checked':'' }} style="accent-color:#F5A623;">
                        Jadikan Primary (Floating Button)
                    </label>
                </div>
            </div>
            @empty
            <p style="color:#A1A1AA;">Belum ada nomor WA. Tambahkan di bawah.</p>
            @endforelse
            @if($waSettings->count())
            <button type="submit" class="btn-primary" style="margin-top:0.75rem;">Simpan Perubahan</button>
            @endif
        </form>
    </div>

    {{-- Add New --}}
    <div class="admin-card">
        <h3 style="font-size:0.875rem;font-weight:700;color:#F5A623;text-transform:uppercase;margin:0 0 1.25rem;">Tambah Nomor WhatsApp Baru</h3>
        <form method="POST" action="{{ route('admin.wa.store') }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label class="form-label">Label <span style="color:#F5A623;">*</span></label>
                    <input type="text" name="label" class="form-input" placeholder="Sales / CS / Teknis" required>
                </div>
                <div>
                    <label class="form-label">Nomor WA <span style="color:#F5A623;">*</span></label>
                    <input type="text" name="nomor_wa" class="form-input" placeholder="081331148731" required>
                </div>
            </div>
            <div style="margin-bottom:1rem;">
                <label class="form-label">Template Pesan Default <span style="color:#F5A623;">*</span></label>
                <textarea name="template_pesan" class="form-input" rows="3" placeholder="Halo buyle.id, saya ingin konsultasi mengenai [produk]..." required></textarea>
            </div>
            <button type="submit" class="btn-primary">+ Tambah Nomor</button>
        </form>
    </div>
</div>
@endsection
