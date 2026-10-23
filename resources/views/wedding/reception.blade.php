<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Meja Penerima Tamu · {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        .header-bar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.9rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .header-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
        }

        .header-sub {
            font-size: 0.72rem;
            color: #64748b;
        }

        .gate-badge {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            font-size: 0.68rem;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 999px;
            text-transform: uppercase;
        }

        .container {
            max-width: 640px;
            margin: 1.25rem auto;
            padding: 0 1rem;
        }

        /* Stats Capsule */
        .stats-capsule {
            display: flex;
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 1rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .stats-item {
            flex: 1;
            padding: 0.9rem 0.5rem;
            text-align: center;
            border-right: 1px solid #f1f5f9;
        }

        .stats-item:last-child {
            border-right: none;
        }

        .stats-val {
            font-size: 1.3rem;
            font-weight: 800;
            color: #1eb349;
            line-height: 1.2;
        }

        .stats-lbl {
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        /* Scan Box */
        .scan-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 1.25rem;
            border: 1.5px solid #1eb349;
            margin-bottom: 1.25rem;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.12);
        }

        .scan-input {
            width: 100%;
            height: 48px;
            padding: 0 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-family: monospace;
            font-size: 1.1rem;
            font-weight: 700;
            outline: none;
            text-align: center;
            text-transform: uppercase;
            transition: border-color 0.2s;
        }

        .scan-input:focus {
            border-color: #1eb349;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15);
        }

        .btn-checkin {
            width: 100%;
            height: 46px;
            background: linear-gradient(135deg, #1eb349 0%, #16a34a 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            font-family: inherit;
            margin-top: 0.75rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.25);
        }

        /* Search Autocomplete List */
        .guest-item {
            background: #ffffff;
            border-radius: 14px;
            padding: 0.9rem 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .guest-name {
            font-size: 0.875rem;
            font-weight: 700;
            color: #0f172a;
        }

        .guest-meta {
            font-size: 0.72rem;
            color: #64748b;
        }

        .btn-action-checkin {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
        }

        .badge-checked {
            background: #f1f5f9;
            color: #94a3b8;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.35rem 0.65rem;
            border-radius: 999px;
            white-space: nowrap;
        }

        /* PIN Modal */
        .pin-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(5px);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .pin-modal-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            max-width: 360px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body>

    @if(!$isAuthorized)
        {{-- Modal Input PIN --}}
        <div class="pin-modal-overlay">
            <div class="pin-modal-card">
                <div style="width:48px; height:48px; border-radius:50%; background:#dcfce7; color:#166534; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <div style="font-weight:800; font-size:1.1rem; color:#0f172a; margin-bottom:0.25rem;">Akses Meja Resepsi</div>
                <div style="font-size:0.75rem; color:#64748b; margin-bottom:1.5rem;">
                    Masukkan 4-digit PIN panitia untuk membuka aplikasi scanner buku tamu {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}.
                </div>

                @if(session('pin_error'))
                    <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.5rem; border-radius:8px; font-size:0.75rem; font-weight:700; margin-bottom:1rem;">
                        {{ session('pin_error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('wedding.reception', $wedding->slug) }}">
                    @csrf
                    <input type="password" name="pin" maxlength="6" autofocus placeholder="• • • •" style="width:100%; height:50px; font-size:1.5rem; letter-spacing:0.4em; text-align:center; font-family:monospace; border:2px solid #cbd5e1; border-radius:12px; margin-bottom:1.25rem; outline:none;" required>
                    <button type="submit" class="btn-checkin" style="margin-top:0;">
                        Buka Aplikasi Scanner
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Header Bar --}}
    <div class="header-bar">
        <div>
            <div class="header-title">{{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}</div>
            <div class="header-sub">Meja Resepsi &bull; {{ $wedding->event_location_name ?: 'Venue Acara' }}</div>
        </div>
        <span class="gate-badge">● Live Gate</span>
    </div>

    <div class="container">
        
        {{-- Stats Capsule --}}
        <div class="stats-capsule">
            <div class="stats-item">
                <div class="stats-val" id="dispChecked">{{ $checkedInCount }}</div>
                <div class="stats-lbl">Tamu Hadir</div>
            </div>
            <div class="stats-item">
                <div class="stats-val" style="color:#0284c7;">{{ $totalGuests }}</div>
                <div class="stats-lbl">Total Diundang</div>
            </div>
            <div class="stats-item">
                <div class="stats-val" style="color:#d97706;" id="dispPercentage">
                    {{ $totalGuests > 0 ? round(($checkedInCount / $totalGuests) * 100) : 0 }}%
                </div>
                <div class="stats-lbl">Kehadiran</div>
            </div>
        </div>

        {{-- Quick Scan / QR Input --}}
        <div class="scan-card">
            <div style="font-size:0.8rem; font-weight:700; color:#166534; margin-bottom:0.6rem; display:flex; align-items:center; gap:0.4rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2"></path><path d="M17 3h2a2 2 0 0 1 2 2v2"></path><path d="M21 17v2a2 2 0 0 1-2 2h-2"></path><path d="M7 21H5a2 2 0 0 1-2-2v-2"></path></svg>
                Scan Barcode Tamu / Masukkan Kode QR
            </div>
            <input type="text" id="receptionCodeInput" placeholder="WED-XXXXXX" class="scan-input" autocomplete="off" autofocus>
            <button type="button" onclick="submitReceptionScan()" class="btn-checkin">
                Verifikasi &amp; Check-in Tamu
            </button>
            <div id="scanFeedback" style="margin-top:0.75rem; display:none;"></div>
        </div>

        {{-- Cari Nama Tamu (Autocomplete) --}}
        <div style="margin-bottom:0.75rem;">
            <input type="text" id="guestSearchInput" oninput="filterGuestList()" placeholder="🔍 Cari nama tamu atau grup..." style="width:100%; height:44px; padding:0 1rem; border:1.5px solid #cbd5e1; border-radius:12px; font-size:0.85rem; outline:none; background:#ffffff;">
        </div>

        {{-- List Tamu --}}
        <div id="guestListContainer">
            @foreach($guests as $g)
                <div class="guest-item" data-name="{{ strtolower($g->name) }}" data-group="{{ strtolower($g->group_name) }}" data-qr="{{ strtolower($g->qr_code) }}">
                    <div>
                        <div class="guest-name">{{ $g->name }}</div>
                        <div class="guest-meta">
                            {{ $g->group_name ?: 'Tamu Undangan' }} &bull; <strong>{{ $g->pax ?: 1 }} Pax</strong> &bull; <code style="font-size:0.7rem; background:#f1f5f9; padding:1px 4px; border-radius:3px;">{{ $g->qr_code }}</code>
                        </div>
                    </div>
                    <div>
                        @if($g->is_checked_in)
                            <span class="badge-checked">✓ Hadir ({{ $g->checked_in_at ? $g->checked_in_at->format('H:i') : '' }})</span>
                        @else
                            <button type="button" onclick="checkinById({{ $g->id }}, '{{ addslashes($g->name) }}')" class="btn-action-checkin">
                                + Check-in
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script>
        // Check-in via Input Code / QR
        function submitReceptionScan() {
            const input = document.getElementById('receptionCodeInput');
            const code = input.value.trim();
            if (!code) return;
            executeCheckin({ code: code });
        }

        // Check-in via Tombol Tamu
        function checkinById(guestId, name) {
            if (!confirm(`Check-in untuk "${name}"?`)) return;
            executeCheckin({ guest_id: guestId });
        }

        // Eksekusi API Checkin
        function executeCheckin(payload) {
            const feedback = document.getElementById('scanFeedback');
            feedback.style.display = 'block';
            feedback.innerHTML = '<span style="color:#0284c7; font-size:0.75rem; font-weight:700;">Sedang mencatat kehadiran...</span>';

            fetch("{{ route('wedding.reception.checkin', $wedding->slug) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    feedback.innerHTML = `<div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.6rem; border-radius:8px; font-size:0.8rem; font-weight:700;">🎉 ${data.message} (${data.guest.pax} Pax)</div>`;
                    document.getElementById('receptionCodeInput').value = '';
                    setTimeout(() => location.reload(), 1200);
                } else {
                    feedback.innerHTML = `<div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.6rem; border-radius:8px; font-size:0.8rem; font-weight:700;">❌ ${data.message}</div>`;
                }
            })
            .catch(err => {
                feedback.innerHTML = `<div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.6rem; border-radius:8px; font-size:0.8rem; font-weight:700;">❌ Gagal menghubungi server.</div>`;
            });
        }

        // Live Filter Tamu
        function filterGuestList() {
            const query = document.getElementById('guestSearchInput').value.toLowerCase().trim();
            const items = document.querySelectorAll('.guest-item');

            items.forEach(item => {
                const name = item.getAttribute('data-name');
                const group = item.getAttribute('data-group');
                const qr = item.getAttribute('data-qr');

                if (name.includes(query) || group.includes(query) || qr.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Enter key di scan input
        document.getElementById('receptionCodeInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                submitReceptionScan();
            }
        });
    </script>
</body>
</html>
