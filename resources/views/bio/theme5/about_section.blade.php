@php
    $bioName   = $config['name'] ?? $profile->store_name ?? $username;
    $bioText   = $config['bio'] ?? $profile->store_description ?? 'Konten kreator & pengembang karya digital terpercaya.';
    $location  = $config['location'] ?? $profile->city ?? 'Indonesia';
    $roleTitle = $profile->bio_role ? ucfirst(str_replace('_', ' ', $profile->bio_role)) : 'Official Creator';
    $avatarUrl = !empty($config['avatar']) 
        ? asset('storage/' . $config['avatar']) 
        : (!empty($config['_user_avatar']) ? (Str::startsWith($config['_user_avatar'], ['http://', 'https://']) ? $config['_user_avatar'] : asset('storage/' . $config['_user_avatar'])) : null);
@endphp

<section class="t5-about-section" id="about-section">
    <div class="t5-about-card">
        <div class="t5-about-header">
            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="{{ $bioName }}" class="t5-about-avatar">
            @else
                <div class="t5-about-avatar-fallback">{{ strtoupper(substr($bioName, 0, 2)) }}</div>
            @endif

            <div class="t5-about-meta">
                <div class="t5-about-badges">
                    <span class="t5-role-badge">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        {{ $roleTitle }}
                    </span>
                    <span class="t5-verified-badge">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        Terverifikasi buyle.id
                    </span>
                </div>

                <h2 class="t5-about-name">{{ $bioName }}</h2>

                @if(!empty($location))
                    <div class="t5-about-loc">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        {{ $location }}
                    </div>
                @endif
            </div>
        </div>

        <div class="t5-about-body">
            <p class="t5-about-bio">{{ $bioText }}</p>

            {{-- Include Social Icons (all SVG vector icons) --}}
            <div class="t5-social-wrap">
                @include('bio._social_icons', ['profile' => $profile, 'config' => $config])
            </div>
        </div>
    </div>
</section>
