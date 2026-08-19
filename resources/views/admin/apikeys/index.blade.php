@extends('layouts.admin')
@section('title','API & Integrasi')
@section('page-title','API & Integrasi')
@section('content')

<style>
.api-tabs { display:flex;gap:2rem;border-bottom:1px solid #F1F5F9;margin-bottom:2rem; }
.api-tab { padding:1rem 1.5rem;font-size:.95rem;font-weight:700;color:#94A3B8;cursor:pointer;border-bottom:2px solid transparent;transition:all .2s; }
.api-tab.active { color:#DC2626;background:#FEF2F2;border-bottom-color:#DC2626;border-top-left-radius:8px;border-top-right-radius:8px; }
.api-table { width:100%;border-collapse:collapse; }
.api-table th { text-align:left;padding:1rem 1.5rem;font-size:.875rem;font-weight:700;color:#475569;background:#F8FAFC;border-bottom:1px solid #F1F5F9; }
.api-table td { padding:1.25rem 1.5rem;font-size:.9rem;color:#1E293B;border-bottom:1px solid #F1F5F9;font-weight:500; }
.api-table tr:hover td { background:#FAFBFF; }
.api-key-hidden { color:#DC2626;display:inline-flex;align-items:center;gap:.5rem;font-family:monospace;font-size:.95rem; }

/* Environment Toggle */
.env-toggle-wrap { display:flex;align-items:center;gap:1rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:14px;padding:1rem 1.5rem;margin-bottom:1.75rem; }
.env-badge { display:inline-flex;align-items:center;gap:.5rem;padding:.35rem .9rem;border-radius:20px;font-size:.8rem;font-weight:700;letter-spacing:.5px; }
.env-badge.sandbox { background:#FEF3C7;color:#92400E; }
.env-badge.live    { background:#DCFCE7;color:#166534; }
.env-toggle-switch { position:relative;display:inline-block;width:52px;height:28px;cursor:pointer; }
.env-toggle-switch input { display:none; }
.env-slider { position:absolute;inset:0;background:#CBD5E1;border-radius:28px;transition:.3s; }
.env-slider:before { content:'';position:absolute;width:22px;height:22px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.3s;box-shadow:0 2px 4px rgba(0,0,0,.15); }
input:checked + .env-slider { background:#16A34A; }
input:checked + .env-slider:before { transform:translateX(24px); }
</style>

@if(session('success'))
  <div style="background:#F0FDF4;color:#166534;padding:1rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;border:1px solid #BBF7D0;font-size:.875rem;">{{ session('success') }}</div>
@endif

<div class="api-tabs">
    <div class="api-tab active">API Key</div>
    <div class="api-tab">Access</div>
</div>

{{-- ============================================================ --}}
{{-- KOMERCE ENVIRONMENT TOGGLE                                    --}}
{{-- ============================================================ --}}
@php $isLive = $settings->get('komerce_mode','sandbox') === 'live'; @endphp

<div style="background:#fff;border-radius:16px;border:1px solid #E2E8F0;box-shadow:0 4px 20px rgba(0,0,0,0.03);overflow:hidden;padding:2rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.5rem;">
        <div style="width:36px;height:36px;border-radius:10px;background:#FEF2F2;display:flex;align-items:center;justify-content:center;">
            <svg width="18" height="18" fill="none" stroke="#DC2626" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
        </div>
        <div>
            <h3 style="font-size:1rem;font-weight:800;color:#1E293B;margin:0;">Mode Komerce API</h3>
            <p style="font-size:.8rem;color:#64748B;margin:0;">Pilih environment yang digunakan untuk Shipping Cost & Cek Resi</p>
        </div>
        <span class="env-badge {{ $isLive ? 'live' : 'sandbox' }}" id="env-badge-current">
            {{ $isLive ? '🟢 LIVE' : '🟡 SANDBOX' }}
        </span>
    </div>

    <form action="{{ route('admin.apikeys.update') }}" method="POST" id="form-env-mode">
        @csrf
        <input type="hidden" name="komerce_mode" id="input-komerce-mode" value="{{ $settings->get('komerce_mode','sandbox') }}">
        <div class="env-toggle-wrap">
            <span style="font-size:.9rem;font-weight:600;color:#64748B;">Sandbox</span>
            <label class="env-toggle-switch">
                <input type="checkbox" id="toggle-env" {{ $isLive ? 'checked' : '' }} onchange="switchEnvMode(this)">
                <span class="env-slider"></span>
            </label>
            <span style="font-size:.9rem;font-weight:600;color:#64748B;">Live / Production</span>
            <span style="flex:1;"></span>
            <button type="submit" style="padding:.6rem 1.25rem;background:#DC2626;color:#fff;border:none;border-radius:10px;font-weight:700;cursor:pointer;font-size:.85rem;font-family:inherit;">
                Simpan Mode
            </button>
        </div>
        <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px;padding:.75rem 1rem;font-size:.82rem;color:#92400E;display:flex;gap:.5rem;">
            <span>⚠️</span>
            <span>
                <b>Sandbox</b>: Gunakan key sandbox dari Komerce — untuk testing & development.<br>
                <b>Live</b>: Gunakan key production dari Komerce — untuk website yang sudah tayang.
            </span>
        </div>
    </form>
</div>

{{-- ============================================================ --}}
{{-- API KEY TABLE                                                 --}}
{{-- ============================================================ --}}
<div style="background:#fff;border-radius:16px;border:1px solid #E2E8F0;box-shadow:0 4px 20px rgba(0,0,0,0.03);overflow:hidden;padding:2rem;">
    <h2 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0 0 .5rem;">Api Key List</h2>
    <p style="font-size:.9rem;color:#64748B;margin:0 0 2rem;">Manage API Keys for every services that you use.</p>

    <div style="border-radius:12px;overflow:hidden;border:1px solid #F1F5F9;">
        <table class="api-table">
            <thead>
                <tr>
                    <th style="width:60px;text-align:center;">#</th>
                    <th style="width:200px;">API Name</th>
                    <th>Sandbox Key</th>
                    <th>Live / Production Key</th>
                    <th style="width:100px;text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Shipping Cost --}}
                <tr>
                    <td style="text-align:center;color:#94A3B8;">1</td>
                    <td>
                        Shipping Cost <span style="font-size:.75rem;color:#94A3B8;display:block;font-weight:400;">RajaOngkir Tariff</span>
                        @if($isLive)
                            <span style="display:inline-block;margin-top:.25rem;padding:.1rem .5rem;background:#DCFCE7;color:#166534;border-radius:6px;font-size:.7rem;font-weight:700;">LIVE</span>
                        @else
                            <span style="display:inline-block;margin-top:.25rem;padding:.1rem .5rem;background:#FEF3C7;color:#92400E;border-radius:6px;font-size:.7rem;font-weight:700;">SANDBOX</span>
                        @endif
                    </td>
                    <td>@include('admin.apikeys._key_cell', ['keyVal' => $settings->get('rajaongkir_api_key_sandbox',''), 'active' => !$isLive])</td>
                    <td>@include('admin.apikeys._key_cell', ['keyVal' => $settings->get('rajaongkir_api_key',''), 'active' => $isLive])</td>
                    <td style="text-align:center;">
                        <button onclick="openApiModal('rajaongkir')" style="background:transparent;border:none;color:#3B82F6;font-weight:600;cursor:pointer;font-size:.85rem;">Edit ➔</button>
                    </td>
                </tr>
                {{-- Shipping Delivery --}}
                <tr>
                    <td style="text-align:center;color:#94A3B8;">2</td>
                    <td>
                        Shipping Delivery <span style="font-size:.75rem;color:#94A3B8;display:block;font-weight:400;">Lacak Resi / Waybill</span>
                        @if($isLive)
                            <span style="display:inline-block;margin-top:.25rem;padding:.1rem .5rem;background:#DCFCE7;color:#166534;border-radius:6px;font-size:.7rem;font-weight:700;">LIVE</span>
                        @else
                            <span style="display:inline-block;margin-top:.25rem;padding:.1rem .5rem;background:#FEF3C7;color:#92400E;border-radius:6px;font-size:.7rem;font-weight:700;">SANDBOX</span>
                        @endif
                    </td>
                    <td>@include('admin.apikeys._key_cell', ['keyVal' => $settings->get('shipping_delivery_api_key_sandbox',''), 'active' => !$isLive])</td>
                    <td>@include('admin.apikeys._key_cell', ['keyVal' => $settings->get('shipping_delivery_api_key',''), 'active' => $isLive])</td>
                    <td style="text-align:center;">
                        <button onclick="openApiModal('delivery')" style="background:transparent;border:none;color:#3B82F6;font-weight:600;cursor:pointer;font-size:.85rem;">Edit ➔</button>
                    </td>
                </tr>
                {{-- Payment API --}}
                <tr>
                    <td style="text-align:center;color:#94A3B8;">3</td>
                    <td>Payment API <span style="font-size:.75rem;color:#94A3B8;display:block;font-weight:400;">Midtrans Gateway</span></td>
                    <td colspan="2">
                        <div class="api-key-hidden">
                            <button type="button" onclick="toggleApiKey(this)"
                                data-full="{{ $settings->get('midtrans_server_key', '') }}"
                                data-hidden="{{ $settings->get('midtrans_server_key') ? substr($settings->get('midtrans_server_key'), 0, 5) . str_repeat('•', 20) : 'Belum diatur' }}"
                                style="background:none;border:none;cursor:pointer;color:inherit;display:flex;align-items:center;padding:0;">
                                <svg class="icon-hide" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/></svg>
                                <svg class="icon-show" style="display:none;" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            <span class="key-text" style="color:{{ $settings->get('midtrans_server_key') ? '#DC2626' : '#94A3B8' }}; letter-spacing:1px; font-family:monospace;">
                                {{ $settings->get('midtrans_server_key') ? substr($settings->get('midtrans_server_key'), 0, 5) . str_repeat('•', 20) : 'Belum diatur' }}
                            </span>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <button onclick="openApiModal('midtrans')" style="background:transparent;border:none;color:#3B82F6;font-weight:600;cursor:pointer;font-size:.85rem;">Edit ➔</button>
                    </td>
                </tr>
                {{-- QRISLY API --}}
                <tr>
                    <td style="text-align:center;color:#94A3B8;">4</td>
                    <td>QRISLY API <span style="font-size:.75rem;color:#94A3B8;display:block;font-weight:400;">Qris Payment</span></td>
                    <td colspan="2">
                        <div class="api-key-hidden">
                            <button type="button" onclick="toggleApiKey(this)"
                                data-full="{{ $settings->get('qrisly_api_key', '') }}"
                                data-hidden="{{ $settings->get('qrisly_api_key') ? substr($settings->get('qrisly_api_key'), 0, 5) . str_repeat('•', 20) : 'Belum diatur' }}"
                                style="background:none;border:none;cursor:pointer;color:inherit;display:flex;align-items:center;padding:0;">
                                <svg class="icon-hide" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/></svg>
                                <svg class="icon-show" style="display:none;" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            <span class="key-text" style="color:{{ $settings->get('qrisly_api_key') ? '#DC2626' : '#94A3B8' }}; letter-spacing:1px; font-family:monospace;">
                                {{ $settings->get('qrisly_api_key') ? substr($settings->get('qrisly_api_key'), 0, 5) . str_repeat('•', 20) : 'Belum diatur' }}
                            </span>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <button onclick="openApiModal('qrisly')" style="background:transparent;border:none;color:#3B82F6;font-weight:600;cursor:pointer;font-size:.85rem;">Edit ➔</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: SHIPPING COST (RajaOngkir)                            --}}
{{-- ============================================================ --}}
<div id="modal-rajaongkir" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
  <div style="background:#fff;border-radius:20px;padding:2.5rem;width:100%;max-width:560px;box-shadow:0 24px 64px rgba(0,0,0,0.2);margin:1rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
      <h3 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0;">Shipping Cost (RajaOngkir)</h3>
      <button onclick="closeModal('modal-rajaongkir')" style="background:#F1F5F9;border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:1rem;color:#64748B;">✕</button>
    </div>
    <form action="{{ route('admin.apikeys.update') }}" method="POST">
      @csrf
      {{-- Sandbox Key --}}
      <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:12px;padding:1.25rem;margin-bottom:1.25rem;">
          <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
              <span style="padding:.2rem .6rem;background:#FEF3C7;color:#92400E;border-radius:6px;font-size:.75rem;font-weight:700;">🟡 SANDBOX</span>
              <span style="font-size:.8rem;color:#92400E;">api-sandbox.collaborator.komerce.id</span>
          </div>
          <label style="display:block;font-size:.85rem;font-weight:700;color:#475569;margin-bottom:.5rem;">Sandbox API Key</label>
          <input type="text" name="rajaongkir_api_key_sandbox" value="{{ $settings->get('rajaongkir_api_key_sandbox', '') }}" placeholder="Masukkan Sandbox Shipping Cost Key..." style="width:100%;padding:.875rem 1rem;border:1.5px solid #FDE68A;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;font-family:monospace;">
      </div>
      {{-- Live Key --}}
      <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:12px;padding:1.25rem;margin-bottom:1.75rem;">
          <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
              <span style="padding:.2rem .6rem;background:#DCFCE7;color:#166534;border-radius:6px;font-size:.75rem;font-weight:700;">🟢 LIVE</span>
              <span style="font-size:.8rem;color:#166534;">api.collaborator.komerce.id</span>
          </div>
          <label style="display:block;font-size:.85rem;font-weight:700;color:#475569;margin-bottom:.5rem;">Live / Production API Key</label>
          <input type="text" name="rajaongkir_api_key" value="{{ $settings->get('rajaongkir_api_key', '') }}" placeholder="Masukkan Live Shipping Cost Key..." style="width:100%;padding:.875rem 1rem;border:1.5px solid #BBF7D0;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;font-family:monospace;">
      </div>
      <div style="display:flex;gap:1rem;">
        <button type="button" onclick="closeModal('modal-rajaongkir')" style="flex:1;padding:.875rem;background:#F1F5F9;color:#475569;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Batal</button>
        <button type="submit" style="flex:2;padding:.875rem;background:#DC2626;color:#fff;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Simpan API Key</button>
      </div>
    </form>
  </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: SHIPPING DELIVERY                                      --}}
{{-- ============================================================ --}}
<div id="modal-delivery" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
  <div style="background:#fff;border-radius:20px;padding:2.5rem;width:100%;max-width:560px;box-shadow:0 24px 64px rgba(0,0,0,0.2);margin:1rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
      <h3 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0;">Shipping Delivery API</h3>
      <button onclick="closeModal('modal-delivery')" style="background:#F1F5F9;border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:1rem;color:#64748B;">✕</button>
    </div>
    <form action="{{ route('admin.apikeys.update') }}" method="POST">
      @csrf
      {{-- Sandbox Key --}}
      <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:12px;padding:1.25rem;margin-bottom:1.25rem;">
          <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
              <span style="padding:.2rem .6rem;background:#FEF3C7;color:#92400E;border-radius:6px;font-size:.75rem;font-weight:700;">🟡 SANDBOX</span>
              <span style="font-size:.8rem;color:#92400E;">api-sandbox.collaborator.komerce.id</span>
          </div>
          <label style="display:block;font-size:.85rem;font-weight:700;color:#475569;margin-bottom:.5rem;">Sandbox API Key</label>
          <input type="text" name="shipping_delivery_api_key_sandbox" value="{{ $settings->get('shipping_delivery_api_key_sandbox', '') }}" placeholder="Masukkan Sandbox Shipping Delivery Key..." style="width:100%;padding:.875rem 1rem;border:1.5px solid #FDE68A;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;font-family:monospace;">
      </div>
      {{-- Live Key --}}
      <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:12px;padding:1.25rem;margin-bottom:1.75rem;">
          <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
              <span style="padding:.2rem .6rem;background:#DCFCE7;color:#166534;border-radius:6px;font-size:.75rem;font-weight:700;">🟢 LIVE</span>
              <span style="font-size:.8rem;color:#166534;">api.collaborator.komerce.id</span>
          </div>
          <label style="display:block;font-size:.85rem;font-weight:700;color:#475569;margin-bottom:.5rem;">Live / Production API Key</label>
          <input type="text" name="shipping_delivery_api_key" value="{{ $settings->get('shipping_delivery_api_key', '') }}" placeholder="Masukkan Live Shipping Delivery Key..." style="width:100%;padding:.875rem 1rem;border:1.5px solid #BBF7D0;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;font-family:monospace;">
      </div>
      <div style="display:flex;gap:1rem;">
        <button type="button" onclick="closeModal('modal-delivery')" style="flex:1;padding:.875rem;background:#F1F5F9;color:#475569;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Batal</button>
        <button type="submit" style="flex:2;padding:.875rem;background:#DC2626;color:#fff;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Simpan API Key</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL MIDTRANS --}}
<div id="modal-midtrans" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
  <div style="background:#fff;border-radius:20px;padding:2.5rem;width:100%;max-width:500px;box-shadow:0 24px 64px rgba(0,0,0,0.2);margin:1rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
      <h3 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0;">Payment API (Midtrans)</h3>
      <button onclick="closeModal('modal-midtrans')" style="background:#F1F5F9;border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:1rem;color:#64748B;">✕</button>
    </div>
    <form action="{{ route('admin.apikeys.update') }}" method="POST">
      @csrf
      <div style="margin-bottom:1.25rem;">
        <label style="display:block;font-size:.85rem;font-weight:700;color:#475569;margin-bottom:.5rem;">Environment</label>
        <select name="midtrans_is_production" style="width:100%;padding:.875rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.95rem;outline:none;background:#fff;font-family:inherit;">
          <option value="0" {{ $settings->get('midtrans_is_production', '0') == '0' ? 'selected' : '' }}>Sandbox (Mode Testing)</option>
          <option value="1" {{ $settings->get('midtrans_is_production', '0') == '1' ? 'selected' : '' }}>Production (Live)</option>
        </select>
      </div>
      <div style="margin-bottom:1.25rem;">
        <label style="display:block;font-size:.85rem;font-weight:700;color:#475569;margin-bottom:.5rem;">Server Key</label>
        <input type="text" name="midtrans_server_key" value="{{ $settings->get('midtrans_server_key', '') }}" required style="width:100%;padding:.875rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.95rem;outline:none;box-sizing:border-box;font-family:inherit;">
      </div>
      <div style="margin-bottom:2rem;">
        <label style="display:block;font-size:.85rem;font-weight:700;color:#475569;margin-bottom:.5rem;">Client Key</label>
        <input type="text" name="midtrans_client_key" value="{{ $settings->get('midtrans_client_key', '') }}" required style="width:100%;padding:.875rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.95rem;outline:none;box-sizing:border-box;font-family:inherit;">
      </div>
      <div style="display:flex;gap:1rem;">
        <button type="button" onclick="closeModal('modal-midtrans')" style="flex:1;padding:.875rem;background:#F1F5F9;color:#475569;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Batal</button>
        <button type="submit" style="flex:2;padding:.875rem;background:#DC2626;color:#fff;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Simpan API Key</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL QRISLY --}}
<div id="modal-qrisly" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
  <div style="background:#fff;border-radius:20px;padding:2.5rem;width:100%;max-width:500px;box-shadow:0 24px 64px rgba(0,0,0,0.2);margin:1rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
      <h3 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0;">QRISLY API</h3>
      <button onclick="closeModal('modal-qrisly')" style="background:#F1F5F9;border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:1rem;color:#64748B;">✕</button>
    </div>
    <form action="{{ route('admin.apikeys.update') }}" method="POST">
      @csrf
      <div style="margin-bottom:2rem;">
        <label style="display:block;font-size:.85rem;font-weight:700;color:#475569;margin-bottom:.5rem;">API Key</label>
        <input type="text" name="qrisly_api_key" value="{{ $settings->get('qrisly_api_key', '') }}" required style="width:100%;padding:.875rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.95rem;outline:none;box-sizing:border-box;font-family:inherit;">
      </div>
      <div style="display:flex;gap:1rem;">
        <button type="button" onclick="closeModal('modal-qrisly')" style="flex:1;padding:.875rem;background:#F1F5F9;color:#475569;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Batal</button>
        <button type="submit" style="flex:2;padding:.875rem;background:#DC2626;color:#fff;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Simpan API Key</button>
      </div>
    </form>
  </div>
</div>

<script>
function openApiModal(type) {
    document.getElementById('modal-' + type).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
['modal-rajaongkir','modal-midtrans','modal-delivery','modal-qrisly'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e){ if(e.target===this) closeModal(id); });
});

function switchEnvMode(checkbox) {
    const mode = checkbox.checked ? 'live' : 'sandbox';
    document.getElementById('input-komerce-mode').value = mode;
    const badge = document.getElementById('env-badge-current');
    if (mode === 'live') {
        badge.textContent = '🟢 LIVE';
        badge.className = 'env-badge live';
    } else {
        badge.textContent = '🟡 SANDBOX';
        badge.className = 'env-badge sandbox';
    }
}

function toggleApiKey(btn) {
    const container = btn.closest('.api-key-hidden');
    const textSpan  = container.querySelector('.key-text');
    const iconHide  = btn.querySelector('.icon-hide');
    const iconShow  = btn.querySelector('.icon-show');
    const fullKey   = btn.getAttribute('data-full');
    const hiddenKey = btn.getAttribute('data-hidden');
    if (!fullKey) return;
    if (textSpan.textContent.trim() === hiddenKey) {
        textSpan.textContent = fullKey;
        iconHide.style.display = 'none';
        iconShow.style.display = 'block';
    } else {
        textSpan.textContent = hiddenKey;
        iconHide.style.display = 'block';
        iconShow.style.display = 'none';
    }
}
</script>

@endsection
