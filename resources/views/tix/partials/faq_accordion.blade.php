{{-- ══════════════════════════════════════════════════════════════
BUYLE TIX — FAQ ACCORDION COMPONENT (LIGHT THEME & INTERACTIVE)
══════════════════════════════════════════════════════════════ --}}
@php
    $faqItems = [
        [
            'q' => 'Bagaimana cara membeli tiket di Buyle TIX?',
            'badge' => 'Pembelian',
            'a' => '
                        <ol style="margin:0; padding-left:1.25rem; display:flex; flex-direction:column; gap:0.45rem;">
                            <li>Pilih event yang Anda inginkan di <strong>buyle.id/tix</strong> atau melalui link event langsung.</li>
                            <li>Pilih kategori tiket dan tentukan jumlah tiket yang ingin dibeli.</li>
                            <li>Klik <strong>Beli Sekarang</strong> lalu lengkapi data pemegang tiket (Nama lengkap & nomor identitas resmi seperti KTP, SIM, Paspor, atau Kartu Pelajar).</li>
                            <li>Pilih metode pembayaran (QRIS instan, Virtual Account, atau Transfer Bank).</li>
                            <li>Selesaikan pembayaran sebelum batas waktu countdown habis.</li>
                            <li>E-tiket resmi ber-QR Code akan langsung dikirimkan ke email Anda dan tersimpan otomatis di menu <strong>Tiket Saya</strong> pada Dashboard akun Buyle Anda.</li>
                        </ol>'
        ],
        [
            'q' => 'Bagaimana mekanisme masuk (check-in) di hari H acara?',
            'badge' => 'Hari H Acara',
            'a' => '
                        <p style="margin:0 0 0.5rem;">Proses check-in di venue sangat cepat, modern, dan anti-ribet:</p>
                        <ul style="margin:0; padding-left:1.25rem; display:flex; flex-direction:column; gap:0.45rem;">
                            <li>Cukup tunjukkan <strong>Barcode / QR Code E-Tiket</strong> langsung dari layar smartphone Anda (atau hasil cetak) kepada panitia di pintu masuk (gate).</li>
                            <li>Panitia akan memindai menggunakan <strong>Buyle Gate Scanner</strong> resmi. Proses validasi berlangsung kilat <strong>kurang dari 1 detik</strong> per tiket.</li>
                            <li>Pastikan membawa kartu identitas asli (KTP/SIM/Paspor/Kartu Pelajar) yang sesuai dengan data saat pemesanan untuk pencocokan identitas jika diminta oleh panitia.</li>
                        </ul>'
        ],
        [
            'q' => 'Bagaimana kebijakan pembatalan dan pengembalian dana (refund)?',
            'badge' => 'Kebijakan',
            'a' => '
                        <p style="margin:0 0 0.45rem;">Seluruh tiket yang telah berhasil dibeli dan dibayar bersifat <strong>non-refundable</strong> (tidak dapat dibatalkan, ditukar, atau diuangkan kembali), kecuali acara resmi dibatalkan secara sepihak oleh Event Organizer (EO) yang bersangkutan.</p>
                        <p style="margin:0;">Mohon pastikan jadwal acara, kategori tiket, dan data identitas pemegang tiket sudah benar sebelum Anda melakukan pembayaran.</p>'
        ],
        [
            'q' => 'Apakah e-tiket perlu ditukarkan menjadi tiket fisik atau gelang (wristband)?',
            'badge' => 'Penukaran Tiket',
            'a' => '
                        <p style="margin:0 0 0.45rem;">Untuk sebagian besar event di Buyle TIX, Anda <strong>tidak perlu menukar tiket fisik</strong> karena sistem check-in sudah 100% digital via scan QR code di pintu masuk.</p>
                        <p style="margin:0 0 0.45rem;">Namun, jika pihak Event Organizer (EO) mewajibkan penukaran gelang fisik (wristband) sebelum masuk, informasi jadwal dan lokasi penukaran akan diumumkan resmi via deskripsi event & akun media sosial penyelenggara.</p>
                        <p style="margin:0;"><strong>Syarat saat penukaran fisik:</strong> Bawa e-tiket asli (digital/print) dan kartu identitas pemegang tiket yang sesuai.</p>'
        ],
        [
            'q' => 'Apakah Buyle TIX menyediakan pengadaan tiket gelang (wristband) untuk EO?',
            'badge' => 'Layanan EO',
            'a' => '
                        <p style="margin:0 0 0.45rem;"><strong>Ya, tentu!</strong> Buyle TIX menyediakan layanan lengkap pengadaan tiket gelang (wristband) premium untuk event organizer:</p>
                        <ul style="margin:0 0 0.5rem; padding-left:1.25rem; display:flex; flex-direction:column; gap:0.35rem;">
                            <li>Gelang kain woven, Tyvek anti-air (waterproof), dan material vinyl tahan sobek.</li>
                            <li>Dukungan custom barcode unik, QR code, atau RFID chip yang terintegrasi langsung dengan gate scanner Buyle TIX.</li>
                        </ul>
                        <p style="margin:0;">Untuk pemesanan tiket gelang atau kerja sama kemitraan ticketing acara Anda, silakan hubungi tim marketing kami.</p>'
        ],
        [
            'q' => 'Apa saja metode pembayaran yang didukung di Buyle TIX?',
            'badge' => 'Pembayaran',
            'a' => '
                        <p style="margin:0 0 0.5rem;">Buyle TIX mendukung berbagai opsi pembayaran aman, instan, dan terverifikasi otomatis 24/7:</p>
                        <ul style="margin:0; padding-left:1.25rem; display:flex; flex-direction:column; gap:0.35rem;">
                            <li><strong>QRIS Realtime:</strong> Dapat dibayar menggunakan semua aplikasi Mobile Banking (BCA, Mandiri, BRI, BNI, CIMB, dll.) dan seluruh e-wallet (GoPay, OVO, DANA, ShopeePay, LinkAja).</li>
                            <li><strong>Virtual Account (VA):</strong> Pembayaran otomatis tanpa perlu upload bukti transfer.</li>
                            <li><strong>Transfer Bank & Escrow Aman:</strong> Transaksi Anda dilindungi sistem keamanan perbankan terenkripsi.</li>
                        </ul>'
        ],
        [
            'q' => 'Bagaimana jika email e-tiket belum diterima setelah pembayaran?',
            'badge' => 'Bantuan',
            'a' => '
                        <p style="margin:0 0 0.45rem;">Jika email konfirmasi belum muncul di inbox utama Anda:</p>
                        <ol style="margin:0 0 0.5rem; padding-left:1.25rem; display:flex; flex-direction:column; gap:0.35rem;">
                            <li>Periksa folder <strong>Spam / Junk</strong> atau tab <strong>Promotions</strong> di akun email Anda.</li>
                            <li>Masuk ke akun Buyle Anda di <strong>buyle.id</strong>, buka menu profil lalu pilih <strong>Tiket Saya</strong>. Semua e-tiket yang sudah lunas dapat diunduh (PDF) dan dilihat QR kodenya kapan saja.</li>
                        </ol>
                        <p style="margin:0;">Masih butuh bantuan? Tim Customer Support Buyle TIX siap membantu Anda dengan cepat.</p>'
        ],
    ];

    $accordionPrefix = $accordionPrefix ?? 'tix_faq';
@endphp

<div class="tix-faq-wrapper">
    <style>
        .tix-faq-wrapper,
        .tix-faq-wrapper * {
            width: 100%;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif !important;
        }

        .tix-faq-container {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .tix-faq-card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .tix-faq-card:hover {
            border-color: #CBD5E1;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.06);
        }

        .tix-faq-card.is-open {
            border-color: #86EFAC;
            box-shadow: 0 4px 16px rgba(30, 179, 73, 0.1);
        }

        .tix-faq-trigger {
            width: 100%;
            background: none;
            border: none;
            padding: 1.1rem 1.25rem;
            text-align: left;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            cursor: pointer;
            outline: none;
            transition: background 0.2s ease;
        }

        .tix-faq-card.is-open .tix-faq-trigger {
            background: #F0FDF4;
        }

        .tix-faq-q-left {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            min-width: 0;
            flex: 1;
        }

        .tix-faq-num {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #F1F5F9;
            color: #64748B;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
            transition: all 0.2s ease;
        }

        .tix-faq-card.is-open .tix-faq-num {
            background: #1eb349;
            color: #ffffff;
        }

        .tix-faq-q-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0F172A;
            line-height: 1.45;
            margin: 0;
            overflow-wrap: anywhere;
            transition: color 0.2s ease;
        }

        .tix-faq-card.is-open .tix-faq-q-text {
            color: #15803D;
        }

        .tix-faq-tag {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 2px 8px;
            border-radius: 6px;
            background: #E2E8F0;
            color: #475569;
            white-space: nowrap;
        }

        .tix-faq-card.is-open .tix-faq-tag {
            background: #DCFCE7;
            color: #166534;
        }

        .tix-faq-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #F8FAFC;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748B;
            flex-shrink: 0;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .tix-faq-card.is-open .tix-faq-icon {
            background: #DCFCE7;
            color: #15803D;
            transform: rotate(180deg);
        }

        .tix-faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.32s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease;
            opacity: 0;
        }

        .tix-faq-card.is-open .tix-faq-answer {
            opacity: 1;
        }

        .tix-faq-answer-inner {
            padding: 0 1.25rem 1.75rem 1.25rem;
            font-size: 0.88rem;
            color: #334155;
            line-height: 1.7;
            border-top: 1px dashed transparent;
        }

        .tix-faq-card.is-open .tix-faq-answer-inner {
            border-top-color: #E2E8F0;
            padding-top: 1rem;
        }

        /* ── FOOTER HELP BOX ── */
        .tix-faq-help-box {
            margin-top: 1.5rem;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .tix-faq-help-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #1eb349;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.65rem 1.25rem;
            border-radius: 99px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.2);
        }

        .tix-faq-help-btn:hover {
            background: #17963d;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3);
            color: #ffffff;
        }

        @media (max-width: 640px) {
            .tix-faq-trigger {
                padding: 0.9rem 1rem;
                gap: 0.75rem;
            }

            .tix-faq-q-left {
                gap: 0.65rem;
            }

            .tix-faq-num {
                width: 24px;
                height: 24px;
                font-size: 0.7rem;
                border-radius: 6px;
            }

            .tix-faq-q-text {
                font-size: 0.88rem;
                line-height: 1.4;
            }

            .tix-faq-tag {
                display: none;
                /* Hide tag on small mobile for clean anti-cramped display */
            }

            .tix-faq-icon {
                width: 24px;
                height: 24px;
            }

            .tix-faq-answer-inner {
                padding: 0 1rem 1rem 1rem;
                font-size: 0.83rem;
                line-height: 1.6;
            }

            .tix-faq-help-box {
                padding: 1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .tix-faq-help-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="tix-faq-container" id="{{ $accordionPrefix }}_container">
        @foreach($faqItems as $idx => $item)
            @php
                $isFirst = ($idx === 0);
                $cardId = $accordionPrefix . '_item_' . $idx;
            @endphp
            <div class="tix-faq-card {{ $isFirst ? 'is-open' : '' }}" id="{{ $cardId }}">
                <button type="button" class="tix-faq-trigger" onclick="tixToggleFaq('{{ $cardId }}')"
                    aria-expanded="{{ $isFirst ? 'true' : 'false' }}">
                    <div class="tix-faq-q-left">
                        <span class="tix-faq-num">{{ $idx + 1 }}</span>
                        <div>
                            <h3 class="tix-faq-q-text">{{ $item['q'] }}</h3>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:0.5rem; flex-shrink:0; align-self:flex-start; padding-top:2px;">
                        <span class="tix-faq-tag">{{ $item['badge'] }}</span>
                        <div class="tix-faq-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </button>
                <div class="tix-faq-answer" style="{{ $isFirst ? 'max-height: 400px;' : '' }}">
                    <div class="tix-faq-answer-inner">
                        {!! $item['a'] !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        if (typeof window.tixToggleFaq === 'undefined') {
            window.tixToggleFaq = function (cardId) {
                const card = document.getElementById(cardId);
                if (!card) return;

                const isOpen = card.classList.contains('is-open');
                const answer = card.querySelector('.tix-faq-answer');
                const trigger = card.querySelector('.tix-faq-trigger');

                if (isOpen) {
                    card.classList.remove('is-open');
                    if (trigger) trigger.setAttribute('aria-expanded', 'false');
                    if (answer) {
                        answer.style.overflow = 'hidden';
                        answer.style.maxHeight = answer.scrollHeight + 'px';
                        requestAnimationFrame(() => {
                            answer.style.maxHeight = '0px';
                        });
                    }
                } else {
                    card.classList.add('is-open');
                    if (trigger) trigger.setAttribute('aria-expanded', 'true');
                    if (answer) {
                        answer.style.overflow = 'hidden';
                        answer.style.maxHeight = (answer.scrollHeight + 100) + 'px';
                        setTimeout(() => {
                            if (card.classList.contains('is-open')) {
                                answer.style.maxHeight = 'none';
                                answer.style.overflow = 'visible';
                            }
                        }, 360);
                    }
                }
            };
        }
    </script>
</div>