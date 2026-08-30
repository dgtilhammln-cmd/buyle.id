@extends('layouts.admin')
@section('title','Detail Lead #'.$lead->id)
@section('page-title','Detail Lead')

@section('content')
<style>
    .lead-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    @media (min-width: 992px) {
        .lead-grid {
            grid-template-columns: minmax(0, 3fr) 2fr;
        }
    }
    .premium-card {
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
    }
    .premium-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #1E293B;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0 0 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #F1F5F9;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 0.875rem 0;
        border-bottom: 1px solid #F1F5F9;
    }
    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .info-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #64748B;
    }
    .info-value {
        font-size: 0.875rem;
        color: #1E293B;
        font-weight: 700;
        text-align: right;
    }
    .badge {
        padding: 0.375rem 1rem;
        border-radius: 100px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .form-select, .form-input {
        width: 100%;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        color: #1E293B;
        background: #F8FAFC;
        transition: all 0.2s;
        outline: none;
        font-family: inherit;
    }
    .form-select:focus, .form-input:focus {
        border-color: #1eb349;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(30,179,73,0.1);
    }
    
    .btn-primary-new {
        background: linear-gradient(135deg, #1eb349, #a5cf37); color: #fff; border: none; padding: 0.875rem 1.5rem;
        border-radius: 12px; font-weight: 700; font-size: 0.9rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        transition: all 0.2s; box-shadow: 0 4px 14px rgba(30,179,73,0.3); width: 100%;
    }
    .btn-primary-new:hover { background: #1eb349; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(30,179,73,0.4); }

    .message-box {
        background: #F8FAFC;
        padding: 1.25rem;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
    }
    .message-text {
        font-size: 0.95rem;
        color: #334155;
        line-height: 1.7;
        margin: 0;
        white-space: pre-wrap;
    }
    
    .btn-outline-new {
        background: #fff; color: #64748B; border: 1.5px solid #E2E8F0; padding: 0.5rem 1rem;
        border-radius: 10px; font-weight: 600; font-size: 0.85rem; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; gap: 0.35rem;
        transition: all 0.2s; text-decoration: none;
    }
    .btn-outline-new:hover { background: #F8FAFC; color: #1E293B; border-color: #CBD5E1; }
</style>

<div style="max-width:1100px;margin:0 auto;display:flex;flex-direction:column;gap:1.5rem;">

    {{-- Page Header (Above Card) --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Detail Lead #{{ $lead->id }}</h1>
            <p style="font-size:.875rem;color:#94A3B8;margin:0;">Informasi lengkap terkait prospek / request order</p>
        </div>
        <a href="{{ route('admin.leads.index') }}" class="btn-outline-new">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

    {{-- Header Card --}}
    <div class="premium-card" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
        <div style="display:flex;align-items:center;gap:1.25rem;">
            <div style="width:56px;height:56px;border-radius:50%;background:#F1F5F9;display:flex;align-items:center;justify-content:center;color:#1eb349;font-size:1.5rem;font-weight:800;">
                {{ substr($lead->name, 0, 1) }}
            </div>
            <div>
                <h2 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 0.25rem;letter-spacing:-0.01em;">{{ $lead->name }}</h2>
                <div style="font-size:0.85rem;color:#64748B;display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;font-weight:500;">
                    @if($lead->company)
                        <span style="color:#334155;font-weight:700;">{{ $lead->company }}</span> &bull; 
                    @endif
                    Masuk {{ $lead->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
        <span class="badge" style="background:{{ $lead->status_color }}15;color:{{ $lead->status_color }};border:1px solid {{ $lead->status_color }}33;">
            {{ $lead->status_label }}
        </span>
    </div>

    <div class="lead-grid">
        {{-- Kiri: Informasi Kontak --}}
        <div>
            <div class="premium-card">
                <h3 class="premium-title">Informasi Kontak & Metadata</h3>
                <div style="display:flex;flex-direction:column;">
                    @foreach([
                        ['Nomor Telepon / WA', $lead->phone],
                        ['Email', $lead->email ?: '-'],
                        ['Produk Diminati', $lead->product ?: '-'],
                        ['Sumber Referensi', $lead->source],
                        ['UTM Source', $lead->utm_source ?: '-'],
                        ['UTM Medium', $lead->utm_medium ?: '-'],
                        ['UTM Campaign', $lead->utm_campaign ?: '-'],
                        ['UTM Content', $lead->utm_content ?: '-'],
                        ['Perangkat yang Digunakan', ucfirst($lead->device_type)],
                        ['Alamat IP', $lead->ip_address]
                    ] as $row)
                    <div class="info-row">
                        <span class="info-label">{{ $row[0] }}</span>
                        <span class="info-value">{{ $row[1] }}</span>
                    </div>
                    @endforeach
                </div>
                @if($lead->wa_customer)
                <div style="margin-top: 2rem;">
                    <a href="https://wa.me/{{ $lead->wa_customer }}?text={{ urlencode('Halo '.$lead->name.', kami dari buyle.id ingin menindaklanjuti permintaan Anda.') }}" target="_blank"
                       style="display:flex;align-items:center;justify-content:center;gap:0.5rem;background:#10B981;color:#fff;padding:1rem 1.5rem;font-weight:700;font-size:0.95rem;border-radius:12px;text-decoration:none;transition:all 0.2s;box-shadow:0 4px 14px rgba(16,185,129,0.3);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        Follow Up via WhatsApp
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Kanan: Pesan & Status --}}
        <div style="display:flex;flex-direction:column;">
            @if($lead->message)
            <div class="premium-card">
                <h3 class="premium-title">Pesan Customer</h3>
                <div class="message-box">
                    <p class="message-text">{{ $lead->message }}</p>
                </div>
            </div>
            @endif
            
            <div class="premium-card">
                <h3 class="premium-title">Update Status</h3>
                <form method="POST" action="{{ route('admin.leads.status',$lead) }}">
                    @csrf
                    <select name="status" class="form-select" style="margin-bottom:1.25rem;">
                        <option value="new" {{ $lead->status=='new'?'selected':'' }}>Baru (Belum Ditindak)</option>
                        <option value="contacted" {{ $lead->status=='contacted'?'selected':'' }}>Sedang Diproses (Dihubungi)</option>
                        <option value="closed" {{ $lead->status=='closed'?'selected':'' }}>Selesai / Deal / Closed</option>
                    </select>
                    <button type="submit" class="btn-primary-new">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                        Simpan Status
                    </button>
                </form>
            </div>

            <div class="premium-card">
                <h3 class="premium-title">Catatan Internal</h3>
                <form method="POST" action="{{ route('admin.leads.notes',$lead) }}">
                    @csrf
                    <textarea name="notes" class="form-input" rows="5" placeholder="Ketik catatan tambahan untuk admin di sini... (hanya bisa dilihat oleh admin)">{{ $lead->notes }}</textarea>
                    <button type="submit" class="btn-primary-new" style="margin-top:1.25rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Simpan Catatan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
