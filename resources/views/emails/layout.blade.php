<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notifikasi buyle.id' }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F8FAFC; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #1E293B;">
    @php
        $logo = \App\Models\Setting::get('logo');
        $logoUrl = $logo ? url('storage/' . $logo) : null;
        $siteName = \App\Models\Setting::get('site_name', 'buyle.id');
    @endphp

    <!-- Outer Wrapper -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F8FAFC; padding: 40px 16px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #FFFFFF; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03); border: 1px solid #E2E8F0;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td align="center" style="background-color: #0F172A; padding: 36px 32px 32px 32px; background-image: radial-gradient(circle at top right, #1E293B 0%, #0F172A 100%);">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        @if($logoUrl)
                                            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" style="max-height: 44px; width: auto; display: block; border: 0;">
                                        @else
                                            <div style="font-size: 26px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.5px;">
                                                buyle<span style="color: #10B981;">.id</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @if(!empty($badgeText))
                                <tr>
                                    <td align="center" style="padding-top: 16px;">
                                        <span style="display: inline-block; background-color: rgba(16, 185, 129, 0.15); color: #34D399; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; padding: 6px 14px; border-radius: 50px; border: 1px solid rgba(52, 211, 153, 0.3);">
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
                        <td style="padding: 40px 36px 32px 36px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                @if(!empty($title))
                                <tr>
                                    <td align="center" style="padding-bottom: 12px;">
                                        <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #0F172A; line-height: 1.3; letter-spacing: -0.3px;">
                                            {{ $title }}
                                        </h1>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($subtitle))
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <p style="margin: 0; font-size: 14px; color: #64748B; line-height: 1.6;">
                                            {{ $subtitle }}
                                        </p>
                                    </td>
                                </tr>
                                @endif

                                <!-- Main Body HTML -->
                                <tr>
                                    <td style="font-size: 15px; color: #334155; line-height: 1.65;">
                                        {!! $content !!}
                                    </td>
                                </tr>

                                <!-- Primary CTA Button -->
                                @if(!empty($ctaUrl) && !empty($ctaText))
                                <tr>
                                    <td align="center" style="padding-top: 32px; padding-bottom: 16px;">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $ctaUrl }}" style="height:48px;v-text-anchor:middle;width:240px;" arcsize="50%" stroke="f" fillcolor="#10B981">
                                        <w:anchorlock/>
                                        <center style="color:#ffffff;font-family:sans-serif;font-size:15px;font-weight:bold;">{{ $ctaText }}</center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <a href="{{ $ctaUrl }}" style="background-color: #10B981; background-image: linear-gradient(135deg, #10B981 0%, #059669 100%); color: #FFFFFF !important; font-size: 15px; font-weight: 700; text-decoration: none; padding: 14px 34px; border-radius: 50px; display: inline-block; text-align: center; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35); mso-hide: all;">
                                            {{ $ctaText }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                <!-- Secondary CTA Button / Link -->
                                @if(!empty($secondaryCtaUrl) && !empty($secondaryCtaText))
                                <tr>
                                    <td align="center" style="padding-top: 8px; padding-bottom: 16px;">
                                        <a href="{{ $secondaryCtaUrl }}" style="background-color: #F1F5F9; color: #334155 !important; font-size: 14px; font-weight: 600; text-decoration: none; padding: 12px 26px; border-radius: 50px; display: inline-block; text-align: center; border: 1px solid #E2E8F0;">
                                            {{ $secondaryCtaText }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($footerNote))
                                <tr>
                                    <td style="padding-top: 24px; border-top: 1px dashed #E2E8F0; margin-top: 24px;">
                                        <p style="margin: 0; font-size: 13px; color: #94A3B8; line-height: 1.6; text-align: center;">
                                            {!! $footerNote !!}
                                        </p>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    <!-- Footer Section -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 24px 36px; border-top: 1px solid #F1F5F9; text-align: center;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 12px;">
                                        <a href="{{ url('/') }}" style="font-size: 14px; font-weight: 700; color: #0F172A; text-decoration: none;">
                                            buyle<span style="color: #10B981;">.id</span>
                                        </a>
                                        <span style="color: #CBD5E1; margin: 0 8px;">|</span>
                                        <a href="{{ url('/') }}" style="font-size: 13px; color: #64748B; text-decoration: none;">
                                            Platform Layanan & Produk Digital
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.5;">
                                            Butuh bantuan? Balas email ini atau hubungi tim dukungan kami.<br>
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
