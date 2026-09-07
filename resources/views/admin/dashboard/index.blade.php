@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('content')
  @php
    $hour = now()->timezone('Asia/Jakarta')->format('H');
    if ($hour < 11) {
      $greeting = 'pagi';
    } elseif ($hour < 15) {
      $greeting = 'siang';
    } elseif ($hour < 18) {
      $greeting = 'sore';
    } else {
      $greeting = 'malam';
    }

    $adminName = str_ireplace('Cyclevent', 'buyle.id', session('admin_name', 'Admin buyle.id'));
    $currentPeriod = $period ?? '30d';
  @endphp

  <style>
    .dash-card {
      background: #FFFFFF;
      border-radius: 20px;
      border: 1px solid #E2E8F0;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
      padding: 1.5rem;
      transition: all 0.2s ease;
    }

    .dash-card:hover {
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
    }

    .filter-btn {
      padding: 0.5rem 1rem;
      font-size: 0.825rem;
      font-weight: 700;
      border-radius: 50px;
      border: 1.5px solid #E2E8F0;
      background: #fff;
      color: #64748B;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.15s;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
    }

    .filter-btn:hover,
    .filter-btn.active {
      background: #f0fdf4;
      border-color: #1eb349;
      color: #1eb349;
    }

    .order-scroll-box {
      max-height: 420px;
      overflow-y: auto;
      padding-right: 0.35rem;
    }

    .order-scroll-box::-webkit-scrollbar {
      width: 6px;
    }

    .order-scroll-box::-webkit-scrollbar-thumb {
      background: #CBD5E1;
      border-radius: 10px;
    }

    .rank-badge {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 0.785rem;
      flex-shrink: 0;
    }

    .rank-1 {
      background: #FEF3C7;
      color: #D97706;
      border: 1px solid #FCD34D;
    }

    .rank-2 {
      background: #F1F5F9;
      color: #475569;
      border: 1px solid #CBD5E1;
    }

    .rank-3 {
      background: #FFEDD5;
      color: #C2410C;
      border: 1px solid #FDBA74;
    }

    .rank-default {
      background: #F8FAFC;
      color: #94A3B8;
      border: 1px solid #E2E8F0;
    }
  </style>

  {{-- Welcome & Company Header --}}
  <div
    style="background: #ffffff; border: 1px solid #E2E8F0; border-radius: 20px; padding: 1.5rem 2rem; margin-bottom: 1.75rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <div>
      <h2 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; margin: 0 0 0.25rem; letter-spacing: -0.02em;">
        buyle.id Dashboard
      </h2>
      <div style="font-size: 0.85rem; color: #94A3B8; margin-bottom: 0.5rem; font-weight: 500;">
        Ringkasan Performa Sistem — {{ now()->timezone('Asia/Jakarta')->translatedFormat('l, d F Y') }}
      </div>
      <span
        style="display: inline-flex; align-items: center; gap: 0.375rem; background: #f0fdf4; border: 1px solid #bbf7d0; color: #1eb349; font-size: 0.75rem; font-weight: 700; padding: 0.35rem 0.85rem; border-radius: 100px;">
        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
          <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
        </svg>
        HVM Digital System Active
      </span>
    </div>

    <div style="text-align: right; padding-left: 1.5rem; border-left: 1px dashed #E2E8F0;">
      <div style="font-size: 0.85rem; color: #64748B; font-weight: 600; margin-bottom: 0.25rem;">Selamat
        {{ ucfirst($greeting) }},</div>
      <div style="font-size: 1.125rem; font-weight: 800; color: #0F172A; letter-spacing: -0.01em;">{{ $adminName }}</div>
      <div id="realtime-time"
        style="font-family: 'Montserrat', monospace; font-size: 1.5rem; font-weight: 800; color: #1eb349; margin-top: 0.35rem; font-variant-numeric: tabular-nums;">
        00:00:00</div>
    </div>
  </div>

  {{-- Page Header & Time Period Filters --}}
  <div
    style="margin-bottom:1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h1 style="font-size:1.375rem;font-weight:800;color:#0F172A;margin:0 0 .25rem;letter-spacing:-.02em;">Overview
        Analytics</h1>
      <p style="font-size:.8125rem;color:#64748B;margin:0;">Metrik transaksi, visitor, creator, dan produk buyle.id</p>
    </div>

    {{-- FILTER PERIODE FORM --}}
    <form method="GET" action="{{ route('admin.dashboard') }}" id="filter-form"
      style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
      <input type="hidden" name="period" id="period-input" value="{{ $currentPeriod }}">

      {{-- Quick Filter Pills --}}
      <a href="{{ route('admin.dashboard', ['period' => '7d']) }}"
        class="filter-btn {{ $currentPeriod === '7d' ? 'active' : '' }}">7 Hari</a>
      <a href="{{ route('admin.dashboard', ['period' => '30d']) }}"
        class="filter-btn {{ $currentPeriod === '30d' ? 'active' : '' }}">30 Hari</a>
      <a href="{{ route('admin.dashboard', ['period' => '1y']) }}"
        class="filter-btn {{ $currentPeriod === '1y' ? 'active' : '' }}">1 Tahun</a>

      <div style="width: 1px; height: 24px; background: #CBD5E1; margin: 0 0.25rem;"></div>

      {{-- Custom Date Inputs --}}
      <input type="date" id="dash-start" name="start_date" value="{{ $start_date ?? '' }}"
        style="padding: 0.45rem 0.75rem; font-size: 0.8rem; background: #fff; border: 1.5px solid #E2E8F0; border-radius: 50px; color: #1E293B; font-family: inherit; font-weight: 600; outline: none;">
      <span style="color: #94A3B8; font-weight: 600; font-size: 0.8rem;">s/d</span>
      <input type="date" id="dash-end" name="end_date" value="{{ $end_date ?? '' }}"
        style="padding: 0.45rem 0.75rem; font-size: 0.8rem; background: #fff; border: 1.5px solid #E2E8F0; border-radius: 50px; color: #1E293B; font-family: inherit; font-weight: 600; outline: none;">

      <button type="submit" class="filter-btn active" style="background: #1eb349; color: #fff; border-color: #1eb349;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z" />
        </svg>
        Filter
      </button>
    </form>
  </div>

  {{-- 4 STAT CARDS ROW --}}
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;margin-bottom:1.75rem;">
    {{-- Card 1: Total Visitor --}}
    <div class="dash-card">
      <div style="display:flex;align-items:center;gap:1rem;">
        <div
          style="background:rgba(30, 179, 73, 0.1);border-radius:14px;width:52px;height:52px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="26" height="26" fill="none" stroke="#1eb349" stroke-width="2.2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
        </div>
        <div>
          <div style="font-size:0.825rem;color:#64748B;font-weight:600;margin-bottom:0.2rem;">Total Visitor</div>
          <div style="font-size:1.75rem;font-weight:800;color:#0F172A;line-height:1.1;">
            {{ number_format($stats['visitor']) }}</div>
        </div>
      </div>
    </div>

    {{-- Card 2: Total Creators --}}
    <div class="dash-card">
      <div style="display:flex;align-items:center;gap:1rem;">
        <div
          style="background:rgba(5, 150, 105, 0.1);border-radius:14px;width:52px;height:52px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="26" height="26" fill="none" stroke="#059669" stroke-width="2.2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <path
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
        <div>
          <div style="font-size:0.825rem;color:#64748B;font-weight:600;margin-bottom:0.2rem;">Total Creators</div>
          <div style="font-size:1.75rem;font-weight:800;color:#0F172A;line-height:1.1;">
            {{ number_format($stats['creators']) }}</div>
        </div>
      </div>
    </div>

    {{-- Card 3: Total Transaksi (Checkout Count) --}}
    <div class="dash-card">
      <div style="display:flex;align-items:center;gap:1rem;">
        <div
          style="background:rgba(16, 185, 129, 0.1);border-radius:14px;width:52px;height:52px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="26" height="26" fill="none" stroke="#10B981" stroke-width="2.2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="9" cy="21" r="1" />
            <circle cx="20" cy="21" r="1" />
            <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
          </svg>
        </div>
        <div>
          <div style="font-size:0.825rem;color:#64748B;font-weight:600;margin-bottom:0.2rem;">Total Transaksi</div>
          <div style="font-size:1.75rem;font-weight:800;color:#0F172A;line-height:1.1;">
            {{ number_format($stats['transactions']) }} <span
              style="font-size:0.75rem;font-weight:600;color:#94A3B8;">Order</span></div>
        </div>
      </div>
    </div>

    {{-- Card 4: Total Produk Digital --}}
    <div class="dash-card">
      <div style="display:flex;align-items:center;gap:1rem;">
        <div
          style="background:rgba(142, 189, 40, 0.12);border-radius:14px;width:52px;height:52px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="26" height="26" fill="none" stroke="#8ebd28" stroke-width="2.2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <path
              d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z" />
            <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
            <line x1="12" y1="22.08" x2="12" y2="12" />
          </svg>
        </div>
        <div>
          <div style="font-size:0.825rem;color:#64748B;font-weight:600;margin-bottom:0.2rem;">Total Produk Digital</div>
          <div style="font-size:1.75rem;font-weight:800;color:#0F172A;line-height:1.1;">
            {{ number_format($stats['products']) }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- MAIN CHART ROW --}}
  <div class="dash-card" style="margin-bottom: 1.75rem; padding: 1.75rem;">
    <div
      style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
      <div>
        <div
          style="font-size:.8rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.25rem;">
          Grafik Performa Sistem</div>
        <div style="font-size:1.25rem;font-weight:800;color:#0F172A;">Trend Grafik </div>
      </div>
      <div
        style="display:flex;gap:.75rem;align-items:center;padding:.5rem .875rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:100px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:.35rem;font-size:.75rem;font-weight:700;color:#334155;"><span
            style="display:inline-block;width:10px;height:3px;background:#1eb349;border-radius:2px;"></span>Total Visitor
        </div>
        <div style="display:flex;align-items:center;gap:.35rem;font-size:.75rem;font-weight:700;color:#334155;"><span
            style="display:inline-block;width:10px;height:3px;background:#059669;border-radius:2px;"></span>Total Creators
        </div>
        <div style="display:flex;align-items:center;gap:.35rem;font-size:.75rem;font-weight:700;color:#334155;"><span
            style="display:inline-block;width:10px;height:3px;background:#10B981;border-radius:2px;"></span>Total
          Transaksi</div>
        <div style="display:flex;align-items:center;gap:.35rem;font-size:.75rem;font-weight:700;color:#334155;"><span
            style="display:inline-block;width:10px;height:3px;background:#8ebd28;border-radius:2px;"></span>Produk Baru
        </div>
      </div>
    </div>
    <canvas id="main-analytics-chart" height="110"></canvas>
  </div>

  {{-- GRID ROW 2: Klasemen Creator + Riwayat Transaksi Terbaru --}}
  <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">

    {{-- CARD 1: KLASEMEN CREATOR (LEADERBOARD) --}}
    <div class="dash-card">
      <div
        style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #F1F5F9;">
        <div>
          <h3 style="font-size:1.1rem;font-weight:800;color:#0F172A;margin:0 0 0.2rem;">Klasemen Creator</h3>
          <p style="font-size:0.785rem;color:#64748B;margin:0;">Creator dengan traffic &amp; order terbanyak</p>
        </div>
        <span
          style="font-size:0.75rem;font-weight:700;background:#F1F5F9;color:#475569;padding:0.3rem 0.75rem;border-radius:50px;">Top
          10</span>
      </div>

      <div class="order-scroll-box">
        @forelse($creatorsLeaderboard as $idx => $c)
          @php
            $rankClass = match ($idx) { 0 => 'rank-1', 1 => 'rank-2', 2 => 'rank-3', default => 'rank-default'};
            $rankText = '#' . ($idx + 1);
          @endphp
          <div
            style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 0.5rem;border-bottom:1px solid #F8FAFC;gap:0.75rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;overflow:hidden;">
              <div class="rank-badge {{ $rankClass }}">{{ $rankText }}</div>
              @if($c->avatar)
                <img src="{{ $c->avatar }}"
                  style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:1px solid #E2E8F0;flex-shrink:0;">
              @else
                <div
                  style="width:38px;height:38px;border-radius:50%;background:#F1F5F9;color:#64748B;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.85rem;flex-shrink:0;border:1px solid #E2E8F0;">
                  {{ strtoupper(substr($c->name, 0, 1)) }}
                </div>
              @endif
              <div style="overflow:hidden;">
                <a href="{{ route('store.show', ['slug' => $c->slug]) }}" target="_blank"
                  style="font-size:0.875rem;font-weight:700;color:#0F172A;text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"
                  onmouseover="this.style.color='#1eb349'" onmouseout="this.style.color='#0F172A'">
                  {{ $c->name }}
                </a>
                <span style="font-size:0.75rem;color:#94A3B8;">/c/{{ $c->slug }} &bull; {{ $c->products_count }}
                  Produk</span>
              </div>
            </div>

            <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
              <span
                style="font-size:0.75rem;font-weight:700;background:#F0FDF4;color:#166534;padding:0.3rem 0.6rem;border-radius:8px;border:1px solid #BBF7D0;display:inline-flex;align-items:center;gap:3px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
                {{ number_format($c->total_views) }}
              </span>
              <span
                style="font-size:0.75rem;font-weight:700;background:#F0FDF4;color:#15803D;padding:0.3rem 0.6rem;border-radius:8px;border:1px solid #BBF7D0;display:inline-flex;align-items:center;gap:3px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <circle cx="9" cy="21" r="1" />
                  <circle cx="20" cy="21" r="1" />
                  <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
                </svg>
                {{ number_format($c->total_orders) }} Order
              </span>
            </div>
          </div>
        @empty
          <div style="text-align:center;padding:2.5rem 1rem;color:#94A3B8;font-size:0.875rem;">
            Belum ada data klasemen creator.
          </div>
        @endforelse
      </div>
    </div>

    {{-- CARD 2: RIWAYAT TRANSAKSI TERBARU (ORDER) --}}
    <div class="dash-card">
      <div
        style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #F1F5F9;">
        <div>
          <h3 style="font-size:1.1rem;font-weight:800;color:#0F172A;margin:0 0 0.2rem;">Riwayat Transaksi Terbaru</h3>
          <p style="font-size:0.785rem;color:#64748B;margin:0;">Pesanan checkout konsumen paling baru</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
          style="font-size:0.75rem;font-weight:700;color:#1eb349;text-decoration:none;">Refresh</a>
      </div>

      <div class="order-scroll-box">
        @forelse($recentOrders as $ord)
          @php
            $statusVal = is_object($ord->status) ? $ord->status->value : (string) $ord->status;
            $st = match ($statusVal) {
              'pending' => ['#475569', '#F1F5F9', 'Pending'],
              'confirmed' => ['#15803D', '#F0FDF4', 'Terkonfirmasi'],
              'processing' => ['#059669', '#E6F4EA', 'Diproses'],
              'shipped' => ['#047857', '#D1E7DD', 'Dikirim'],
              'completed' => ['#166534', '#DCFCE7', 'Selesai'],
              'cancelled' => ['#991B1B', '#FEE2E2', 'Batal'],
              default => ['#475569', '#F1F5F9', ucfirst($statusVal)]
            };
          @endphp
          <div
            style="padding:0.75rem;border:1px solid #F1F5F9;border-radius:12px;margin-bottom:0.65rem;background:#FAFAFA;transition:all 0.15s;"
            onmouseover="this.style.borderColor='#1eb349';this.style.background='#fff'"
            onmouseout="this.style.borderColor='#F1F5F9';this.style.background='#FAFAFA'">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.35rem;">
              <span
                style="font-size:0.8rem;font-weight:800;color:#0F172A;font-family:monospace;">#{{ $ord->order_number }}</span>
              <span
                style="font-size:0.7rem;font-weight:700;color:{{ $st[0] }};background:{{ $st[1] }};padding:0.2rem 0.5rem;border-radius:50px;">
                {{ $st[2] }}
              </span>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.8rem;">
              <div
                style="color:#475569;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px;display:inline-flex;align-items:center;gap:3px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                  <circle cx="12" cy="7" r="4" />
                </svg>
                {{ $ord->user->name ?? $ord->receiver_name ?? 'Konsumen' }}
              </div>
              <div style="font-weight:800;color:#1eb349;">
                Rp {{ number_format($ord->total ?? 0, 0, ',', '.') }}
              </div>
            </div>
            <div
              style="display:flex;align-items:center;justify-content:space-between;margin-top:0.35rem;font-size:0.725rem;color:#94A3B8;">
              <span>{{ $ord->items_count ?? count($ord->items ?? []) }} Produk</span>
              <span>{{ $ord->created_at ? $ord->created_at->diffForHumans() : '-' }}</span>
            </div>
          </div>
        @empty
          <div style="text-align:center;padding:2.5rem 1rem;color:#94A3B8;font-size:0.875rem;">
            Belum ada riwayat transaksi order.
          </div>
        @endforelse
      </div>
    </div>

  </div>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
      // Realtime Clock Widget
      function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        const el = document.getElementById('realtime-time');
        if (el) el.textContent = `${h}:${m}:${s}`;
      }
      setInterval(updateClock, 1000);
      updateClock();

      // Chart Config
      Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";

      const ctx = document.getElementById('main-analytics-chart').getContext('2d');

      const gVisitor = ctx.createLinearGradient(0, 0, 0, 280);
      gVisitor.addColorStop(0, 'rgba(30,179,73,0.20)');
      gVisitor.addColorStop(1, 'rgba(30,179,73,0.00)');

      const gCreator = ctx.createLinearGradient(0, 0, 0, 280);
      gCreator.addColorStop(0, 'rgba(5,150,105,0.20)');
      gCreator.addColorStop(1, 'rgba(5,150,105,0.00)');

      const gTx = ctx.createLinearGradient(0, 0, 0, 280);
      gTx.addColorStop(0, 'rgba(16,185,129,0.20)');
      gTx.addColorStop(1, 'rgba(16,185,129,0.00)');

      const gProd = ctx.createLinearGradient(0, 0, 0, 280);
      gProd.addColorStop(0, 'rgba(142,189,40,0.20)');
      gProd.addColorStop(1, 'rgba(142,189,40,0.00)');

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: {!! json_encode($labels) !!},
          datasets: [
            {
              label: 'Total Visitor',
              data: {!! json_encode($visitorValues) !!},
              borderColor: '#1eb349',
              backgroundColor: gVisitor,
              borderWidth: 2.5,
              tension: 0.4,
              fill: true,
              pointRadius: 0,
              pointHoverRadius: 6,
              pointHoverBackgroundColor: '#fff',
              pointHoverBorderColor: '#1eb349',
              pointHoverBorderWidth: 2.5,
            },
            {
              label: 'Total Creators',
              data: {!! json_encode($creatorValues) !!},
              borderColor: '#059669',
              backgroundColor: gCreator,
              borderWidth: 2.5,
              tension: 0.4,
              fill: true,
              pointRadius: 0,
              pointHoverRadius: 6,
              pointHoverBackgroundColor: '#fff',
              pointHoverBorderColor: '#059669',
              pointHoverBorderWidth: 2.5,
            },
            {
              label: 'Total Transaksi',
              data: {!! json_encode($transactionValues) !!},
              borderColor: '#10B981',
              backgroundColor: gTx,
              borderWidth: 2.5,
              tension: 0.4,
              fill: true,
              pointRadius: 0,
              pointHoverRadius: 6,
              pointHoverBackgroundColor: '#fff',
              pointHoverBorderColor: '#10B981',
              pointHoverBorderWidth: 2.5,
            },
            {
              label: 'Produk Baru',
              data: {!! json_encode($productValues) !!},
              borderColor: '#8ebd28',
              backgroundColor: gProd,
              borderWidth: 2.5,
              tension: 0.4,
              fill: true,
              pointRadius: 0,
              pointHoverRadius: 6,
              pointHoverBackgroundColor: '#fff',
              pointHoverBorderColor: '#8ebd28',
              pointHoverBorderWidth: 2.5,
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          interaction: { mode: 'index', intersect: false },
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#FFFFFF',
              titleColor: '#0F172A',
              bodyColor: '#475569',
              borderColor: '#E2E8F0',
              borderWidth: 1,
              padding: { x: 14, y: 10 },
              cornerRadius: 12,
              titleFont: { size: 12, weight: '700' },
              bodyFont: { size: 12, weight: '600' }
            }
          },
          scales: {
            x: {
              grid: { display: false },
              border: { display: false },
              ticks: { color: '#94A3B8', font: { size: 11 }, maxTicksLimit: 10 }
            },
            y: {
              grid: { color: '#F1F5F9', borderDash: [5, 4] },
              border: { display: false },
              ticks: { color: '#94A3B8', font: { size: 11 }, stepSize: 1, precision: 0 }
            }
          }
        }
      });
    </script>
  @endpush
@endsection