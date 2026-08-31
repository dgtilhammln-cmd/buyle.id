@extends('creator.layout')

@section('title', 'Membership Seller – Creator Studio')

@section('styles')
<style>
    .cr-dash-header { margin-bottom: 2rem; }
    .cr-dash-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #0b120c;
        letter-spacing: -0.01em;
        margin: 0;
        line-height: 1.2;
    }
    .cr-dash-sub {
        font-size: 0.875rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 0.35rem;
    }

    /* Pricing Grid */
    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 3rem;
    }
    
    /* Pricing Card */
    .plan-card {
        background: #ffffff;
        border: 1.5px solid #f1f5f9;
        border-radius: 28px;
        padding: 2rem 1.5rem;
        position: relative;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }
    .plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    }
    
    /* Highlighted Plan */
    .plan-card.highlight {
        background: linear-gradient(135deg, #1eb349, #4ade80);
        color: #fff;
        border: none;
        box-shadow: 0 15px 35px rgba(30,179,73,0.2);
    }
    
    .plan-badge {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: #0b120c;
        color: #fff;
        font-size: 0.7rem;
        font-weight: 800;
        padding: 0.3rem 1rem;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .plan-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .plan-card.highlight .plan-name { color: #fff; }

    .plan-price {
        font-size: 1.8rem;
        font-weight: 800;
        color: #0b120c;
        text-align: center;
        letter-spacing: -0.02em;
        margin-bottom: 0.25rem;
    }
    .plan-card.highlight .plan-price { color: #fff; }

    .plan-period {
        font-size: 0.8rem;
        color: #64748b;
        text-align: center;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }
    .plan-card.highlight .plan-period { color: rgba(255,255,255,0.8); }

    .plan-features {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .plan-features li {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        font-size: 0.82rem;
        color: #475569;
        font-weight: 500;
        line-height: 1.4;
    }
    .plan-card.highlight .plan-features li { color: #fff; }

    .plan-features li svg {
        flex-shrink: 0;
        color: #1eb349;
        margin-top: 2px;
    }
    .plan-card.highlight .plan-features li svg { color: #fff; }
    
    .plan-features li.cross {
        color: #94a3b8;
        text-decoration: line-through;
    }
    .plan-features li.cross svg {
        color: #cbd5e1;
    }

    .plan-action {
        margin-top: 2rem;
        width: 100%;
        padding: 0.75rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.85rem;
        text-align: center;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .btn-outline {
        background: #fff;
        border: 2px solid #e2e8f0;
        color: #0f172a;
    }
    .btn-outline:hover {
        border-color: #1eb349;
        color: #1eb349;
    }
    
    .btn-primary {
        background: #0b120c;
        color: #fff;
        border: none;
    }
    .btn-primary:hover {
        background: #1eb349;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(30,179,73,0.3);
    }
    
    /* Addon Section */
    .addon-header {
        margin-bottom: 1.5rem;
    }
    .addon-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
    .addon-card {
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        border-radius: 20px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s;
    }
    .addon-card:hover {
        border-color: #1eb349;
        background: #f0fdf4;
    }
    .addon-icon {
        width: 48px;
        height: 48px;
        background: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        color: #1eb349;
    }
    .addon-info { flex: 1; }
    .addon-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .addon-price {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1eb349;
    }

    @media (max-width: 1200px) {
        .pricing-grid { grid-template-columns: repeat(2, 1fr); }
        .addon-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .pricing-grid { grid-template-columns: 1fr; }
        .addon-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<div class="cr-dash-header">
    <h1 class="cr-dash-title">Membership Seller</h1>
    <p class="cr-dash-sub">Tingkatkan visibilitas, nikmati biaya platform lebih rendah, dan unlock fitur eksklusif.</p>
</div>

<div class="pricing-grid">
    {{-- Basic / Free Tier --}}
    <div class="plan-card">
        <div class="plan-name">Basic</div>
        <div class="plan-price">Free</div>
        <div class="plan-period">Selamanya</div>
        
        <ul class="plan-features">
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> 1 Template Premium & 1 Basic</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> 10% Platform fee & withdraw</li>
            <li class="cross"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> X Produk tidak bisa diafiliasikan (Harus Verif Buyer)</li>
            <li class="cross"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Data Buyer</li>
            <li class="cross"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Statistik Visitor</li>
        </ul>
        <a href="#" class="plan-action btn-outline">Current Plan</a>
    </div>

    {{-- Starter Tier --}}
    <div class="plan-card">
        <div class="plan-name">Starter</div>
        <div class="plan-price">Rp 1 Juta</div>
        <div class="plan-period">/ tahun</div>
        
        <ul class="plan-features">
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> 2 Template design premium</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> Data Buyer</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> Statistik Visitor</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> 5% Platform fee & withdraw</li>
        </ul>
        <a href="#" class="plan-action btn-outline">Upgrade Starter</a>
    </div>

    {{-- Growth Tier --}}
    <div class="plan-card">
        <div class="plan-name">Growth</div>
        <div class="plan-price">Rp 1,5 Juta</div>
        <div class="plan-period">/ tahun</div>
        
        <ul class="plan-features">
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> All template Premium unlocked</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> All fitur unlocked</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> 3%-4% Platform fee & withdraw</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> Data Buyer & Statistik</li>
        </ul>
        <a href="#" class="plan-action btn-outline">Upgrade Growth</a>
    </div>

    {{-- Empire Tier --}}
    <div class="plan-card highlight">
        <div class="plan-badge">Most Popular</div>
        <div class="plan-name">Empire</div>
        <div class="plan-price">Rp 2,5 Juta</div>
        <div class="plan-period">/ tahun</div>
        
        <ul class="plan-features">
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> All template exclusive unlocked</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> All fitur unlocked</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> Bonus Ads banner Home & Search</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> Section seller di Homepage</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> 0% Platform fee</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> 0% Biaya withdraw</li>
            <li><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> Unlimited product</li>
        </ul>
        <a href="#" class="plan-action btn-primary">Upgrade Empire</a>
    </div>
</div>

<div class="cr-dash-header addon-header">
    <h3 class="cr-dash-title" style="font-size:1.3rem;">Addons Ekstra</h3>
    <p class="cr-dash-sub">Tingkatkan performa penjualanmu dengan layanan tambahan.</p>
</div>

<div class="addon-grid">
    {{-- Verif Buyer Badge --}}
    <div class="addon-card">
        <div class="addon-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>
        </div>
        <div class="addon-info">
            <div class="addon-title">Verif Buyer Badge</div>
            <div class="addon-price">Rp 200.000 / tahun</div>
        </div>
    </div>

    {{-- Ads Banner Home --}}
    <div class="addon-card">
        <div class="addon-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
        </div>
        <div class="addon-info">
            <div class="addon-title">Ads Banner Home (Kanan)</div>
            <div class="addon-price">Rp 100.000 / bulan</div>
        </div>
    </div>

    {{-- Ads Banner Search --}}
    <div class="addon-card">
        <div class="addon-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </div>
        <div class="addon-info">
            <div class="addon-title">Ads Banner Search (Kanan)</div>
            <div class="addon-price">Rp 65.000 / bulan</div>
        </div>
    </div>
</div>

@endsection
