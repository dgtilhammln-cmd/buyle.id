<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notifikasi buyle.id' }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F0F9FF; font-family: 'Montserrat', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #0F172A;">
    @php
        $logo = \App\Models\Setting::get('logo');
        $logoUrl = $logo ? url('storage/' . $logo) : null;
        $siteName = \App\Models\Setting::get('site_name', 'buyle.id');
    @endphp

    <!-- Outer Wrapper dengan Latar Light Fresh buyle.id -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F0F9FF; padding: 40px 16px; background-image: radial-gradient(at 0% 0%, rgba(56, 189, 248, 0.15) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(30, 179, 73, 0.12) 0px, transparent 50%);">
        <tr>
            <td align="center">
                <!-- Main Container Light Card -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px; background-color: #FFFFFF; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 40px rgba(30, 179, 73, 0.08), 0 4px 16px rgba(0, 0, 0, 0.03); border: 1.5px solid rgba(226, 232, 240, 0.8);">
                    
                    <!-- Top Light Header -->
                    <tr>
                        <td align="center" style="padding: 36px 32px 24px 32px; background-color: #FFFFFF; border-bottom: 1px solid #F1F5F9;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        @if($logoUrl)
                                            <div style="display: inline-block; padding: 8px 20px; border-radius: 999px; background: #FFFFFF; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.04); border: 1px solid #F1F5F9;">
                                                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" style="height: 34px; width: auto; display: block; border: 0;">
                                            </div>
                                        @else
                                            <div style="display: inline-block; padding: 6px 18px; border-radius: 999px; background: linear-gradient(135deg, #1eb349 0%, #10B981 100%); color: #FFFFFF; font-size: 20px; font-weight: 800; letter-spacing: -0.5px;">
                                                buyle.id
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @if(!empty($badgeText))
                                <tr>
                                    <td align="center" style="padding-top: 18px;">
                                        <span style="display: inline-block; background-color: #ECFDF5; color: #1eb349; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px; padding: 6px 16px; border-radius: 50px; border: 1px solid #A7F3D0;">
                                            {{ $badgeText }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 36px 32px 36px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                @if(!empty($title))
                                <tr>
                                    <td align="center" style="padding-bottom: 10px;">
                                        <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #0F172A; line-height: 1.35; letter-spacing: -0.02em;">
                                            {{ $title }}
                                        </h1>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($subtitle))
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <p style="margin: 0; font-size: 14px; font-weight: 500; color: #64748B; line-height: 1.6;">
                                            {{ $subtitle }}
                                        </p>
                                    </td>
                                </tr>
                                @endif

                                <!-- Main Body HTML -->
                                <tr>
                                    <td style="font-size: 15px; color: #334155; line-height: 1.7; font-weight: 500;">
                                        {!! $content !!}
                                    </td>
                                </tr>

                                <!-- Primary CTA Button (Gradasi Hijau buyle.id) -->
                                @if(!empty($ctaUrl) && !empty($ctaText))
                                <tr>
                                    <td align="center" style="padding-top: 28px; padding-bottom: 14px;">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $ctaUrl }}" style="height:48px;v-text-anchor:middle;width:240px;" arcsize="50%" stroke="f" fillcolor="#1eb349">
                                        <w:anchorlock/>
                                        <center style="color:#ffffff;font-family:sans-serif;font-size:15px;font-weight:bold;">{{ $ctaText }}</center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <a href="{{ $ctaUrl }}" style="background-color: #1eb349; background-image: linear-gradient(135deg, #1eb349 0%, #10B981 100%); color: #FFFFFF !important; font-size: 15px; font-weight: 700; text-decoration: none; padding: 14px 34px; border-radius: 50px; display: inline-block; text-align: center; box-shadow: 0 8px 20px rgba(30, 179, 73, 0.25); mso-hide: all; border: none;">
                                            {{ $ctaText }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                <!-- Secondary CTA Button -->
                                @if(!empty($secondaryCtaUrl) && !empty($secondaryCtaText))
                                <tr>
                                    <td align="center" style="padding-top: 8px; padding-bottom: 14px;">
                                        <a href="{{ $secondaryCtaUrl }}" style="background-color: #F8FAFC; color: #334155 !important; font-size: 14px; font-weight: 700; text-decoration: none; padding: 12px 28px; border-radius: 50px; display: inline-block; text-align: center; border: 1.5px solid #E2E8F0;">
                                            {{ $secondaryCtaText }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($footerNote))
                                <tr>
                                    <td style="padding-top: 24px; border-top: 1px dashed #E2E8F0; margin-top: 24px;">
                                        <p style="margin: 0; font-size: 13px; color: #94A3B8; line-height: 1.6; text-align: center; font-weight: 500;">
                                            {!! $footerNote !!}
                                        </p>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    <!-- Light Footer Section -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 24px 36px; border-top: 1px solid #F1F5F9; text-align: center;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 10px;">
                                        <a href="{{ url('/') }}" style="font-size: 14px; font-weight: 800; color: #0F172A; text-decoration: none;">
                                            buyle<span style="color: #1eb349;">.id</span>
                                        </a>
                                        <span style="color: #CBD5E1; margin: 0 8px;">|</span>
                                        <span style="font-size: 13px; color: #64748B; font-weight: 500;">
                                            Toko Serba Ada Produk Digital
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6; font-weight: 500;">
                                            Ada pertanyaan santai? Balas email ini atau hubungi tim bantuan buyle.id.<br>
                                            &copy; {{ date('Y') }} {{ $siteName }}. Hak cipta dilindungi undang-undang.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
