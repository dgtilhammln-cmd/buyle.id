<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notifikasi buyle.id' }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F8FAFC; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; -webkit-font-smoothing: antialiased; color: #1E293B;">
    @php
        $logo = \App\Models\Setting::get('logo');
        $logoUrl = $logo ? url('storage/' . $logo) : null;
        $siteName = \App\Models\Setting::get('site_name', 'buyle.id');
    @endphp

    <!-- Outer Wrapper -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F8FAFC; padding: 40px 16px;">
        <tr>
            <td align="center">
                <!-- Main Container Card -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px; background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); border: 1px solid #E2E8F0;">
                    
                    <!-- Clean Header Logo -->
                    <tr>
                        <td align="center" style="padding: 32px 32px 20px 32px; background-color: #FFFFFF; border-bottom: 1px solid #F1F5F9;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        @if($logoUrl)
                                            <a href="{{ url('/') }}" style="text-decoration: none; display: inline-block;">
                                                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" style="height: 36px; width: auto; display: block; border: 0;">
                                            </a>
                                        @else
                                            <a href="{{ url('/') }}" style="text-decoration: none; font-size: 22px; font-weight: 700; color: #0F172A;">
                                                buyle<span style="color: #10B981;">.id</span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 36px 32px 36px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                @if(!empty($title))
                                <tr>
                                    <td align="center" style="padding-bottom: 12px;">
                                        <h1 style="margin: 0; font-size: 20px; font-weight: 600; color: #0F172A; line-height: 1.4; letter-spacing: -0.01em; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                                            {{ $title }}
                                        </h1>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($subtitle))
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <p style="margin: 0; font-size: 14px; font-weight: 400; color: #64748B; line-height: 1.6;">
                                            {{ $subtitle }}
                                        </p>
                                    </td>
                                </tr>
                                @endif

                                <!-- Main Body HTML -->
                                <tr>
                                    <td style="font-size: 14px; color: #334155; line-height: 1.7; font-weight: 400;">
                                        {!! $content !!}
                                    </td>
                                </tr>

                                <!-- Primary CTA Button -->
                                @if(!empty($ctaUrl) && !empty($ctaText))
                                <tr>
                                    <td align="center" style="padding-top: 28px; padding-bottom: 12px;">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $ctaUrl }}" style="height:46px;v-text-anchor:middle;width:240px;" arcsize="50%" stroke="f" fillcolor="#10B981">
                                        <w:anchorlock/>
                                        <center style="color:#ffffff;font-family:sans-serif;font-size:14px;font-weight:600;">{{ $ctaText }}</center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <a href="{{ $ctaUrl }}" style="background-color: #10B981; background-image: linear-gradient(135deg, #10B981 0%, #059669 100%); color: #FFFFFF !important; font-size: 14px; font-weight: 600; text-decoration: none; padding: 13px 32px; border-radius: 50px; display: inline-block; text-align: center; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25); mso-hide: all; border: none;">
                                            {{ $ctaText }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                <!-- Secondary CTA Button -->
                                @if(!empty($secondaryCtaUrl) && !empty($secondaryCtaText))
                                <tr>
                                    <td align="center" style="padding-top: 8px; padding-bottom: 12px;">
                                        <a href="{{ $secondaryCtaUrl }}" style="background-color: #F8FAFC; color: #475569 !important; font-size: 13px; font-weight: 600; text-decoration: none; padding: 11px 24px; border-radius: 50px; display: inline-block; text-align: center; border: 1px solid #E2E8F0;">
                                            {{ $secondaryCtaText }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($footerNote))
                                <tr>
                                    <td style="padding-top: 24px; border-top: 1px dashed #E2E8F0; margin-top: 24px;">
                                        <p style="margin: 0; font-size: 13px; color: #94A3B8; line-height: 1.6; text-align: center; font-weight: 400;">
                                            {!! $footerNote !!}
                                        </p>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    <!-- Minimal Clean Footer Section -->
                    <tr>
                        <td style="background-color: #FFFFFF; padding: 24px 36px; border-top: 1px solid #F1F5F9; text-align: center;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 6px;">
                                        <a href="{{ url('/') }}" style="font-size: 14px; font-weight: 700; color: #0F172A; text-decoration: none; letter-spacing: -0.01em;">
                                            buyle<span style="color: #10B981;">.id</span>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom: 6px;">
                                        <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.5; font-weight: 400;">
                                            Digital Creator Platform - Marketplace, Link in Bio, Ticketing & Traktir Kreator
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <p style="margin: 0; font-size: 12px; color: #CBD5E1; line-height: 1.5; font-weight: 400;">
                                            &copy; {{ date('Y') }} {{ $siteName }} &nbsp;|&nbsp; Developed by <a href="https://instagram.com/hvmdigital.id" target="_blank" style="color: #94A3B8; font-weight: 500; text-decoration: underline;">HVM Digital</a>
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
