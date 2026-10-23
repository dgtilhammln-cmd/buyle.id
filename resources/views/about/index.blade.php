@extends('layouts.app')

@section('content')

    <style>
        /* ── PREMIUM STYLING ADAPTED FROM HOMEPAGE ── */
        :root {
            --cv-bg: #ffffff;
            --cv-surface: #f8fafc;
            --cv-surface-dark: #f1f5f9;
            --cv-border: #e2e8f0;
            --cv-text: #0f172a;
            --cv-muted: #64748b;
            --cv-accent: #1eb349;
            --cv-accent-green: #10b981;
            --c-muted: #64748B;
            --c-accent: #1eb349;
            --c-text: #0F172A;
            --font: 'Montserrat', sans-serif;
        }

        body {
            background: var(--cv-bg);
            color: var(--cv-text);
        }

        .cv-section-pad {
            padding: 6rem 1.5rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ═══════════════════════════════════════
               PAGE HERO — 100% sama dengan /products
            ═══════════════════════════════════════ */
        .sv-hero-premium {
            position: relative;
            padding: 9rem 1.5rem 5rem;
            background: #F8FAFC;
            overflow: hidden;
            border-bottom: 1px solid #E2E8F0;
        }

        .sv-hero-premium::before {
            content: '';
            position: absolute;
            top: -150px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(30, 179, 73, 0.06) 0%, transparent 70%);
            pointer-events: none;
            border-radius: 50%;
        }

        .sv-hero-premium::after {
            content: '';
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(30, 179, 73, 0.04) 0%, transparent 70%);
            pointer-events: none;
            border-radius: 50%;
        }

        .sv-hero-inner {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .sv-label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #64748B;
            margin-bottom: 1.25rem;
            font-family: var(--font);
        }

        .sv-label::before {
            content: '';
            display: block;
            width: 5px;
            height: 5px;
            background: #1eb349;
            border-radius: 50%;
        }

        .sv-title {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 500;
            color: #0F172A;
            line-height: 1.15;
            letter-spacing: -0.03em;
            font-family: var(--font);
            margin-bottom: 1.5rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .sv-intro {
            margin: 0 auto;
            max-width: 650px;
            font-size: 1rem;
            font-weight: 400;
            color: #64748B;
            line-height: 1.7;
            font-family: var(--font);
        }

        @media (max-width: 640px) {
            .sv-hero-premium {
                padding: 7rem 1rem 4rem;
            }
        }

        /* breadcrumb */
        .sv-breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--c-muted);
            margin-bottom: 2.5rem;
            font-family: var(--font);
        }

        .sv-breadcrumb a {
            color: var(--c-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .sv-breadcrumb a:hover {
            color: var(--c-accent);
        }

        .sv-breadcrumb-sep {
            font-size: 0.6rem;
            color: var(--c-muted);
            opacity: 0.5;
        }

        .sv-breadcrumb-current {
            color: var(--c-text);
            font-weight: 600;
        }

        .cv-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--cv-muted);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .cv-label::before {
            content: '';
            width: 4px;
            height: 4px;
            background: var(--cv-accent);
            border-radius: 50%;
        }

        .cv-heading {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 500;
            line-height: 1.15;
            letter-spacing: -0.03em;
            color: var(--cv-text);
        }

        .cv-heading strong {
            font-weight: 600;
        }

        .cv-heading .ab-icon-blue,
        .cv-heading .ab-icon-green {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1em;
            height: 1em;
            border-radius: 50%;
            vertical-align: middle;
            margin: 0 0.1em;
            transform: translateY(-0.1em);
        }

        .cv-heading .ab-icon-blue {
            background: var(--cv-accent);
            color: #fff;
            padding: 0.2em;
        }

        .cv-heading .ab-icon-green {
            background: var(--cv-accent-green);
            color: #fff;
            padding: 0.2em;
        }

        .cv-desc {
            font-size: 1rem;
            color: var(--cv-muted);
            line-height: 1.7;
            margin-top: 1.5rem;
        }

        /* ── CARDS GRID ── */
        .cv-cards-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
            margin-top: 4rem;
        }

        @media (min-width: 768px) {
            .cv-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .cv-cards-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .cv-card {
            border-radius: 24px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            min-height: 320px;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .cv-card:hover {
            transform: translateY(-5px);
        }

        .cv-card-gray {
            background: var(--cv-surface-dark);
            border: 1px solid var(--cv-border);
        }

        .cv-card-accent {
            background: var(--cv-accent);
            color: #fff;
        }

        .cv-card-image {
            padding: 2rem;
        }

        .cv-card-image .cv-card-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        .cv-card-image .cv-card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 60%);
            z-index: 1;
        }

        .cv-card-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .cv-card-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--cv-muted);
            margin-bottom: 1rem;
        }

        .cv-card-accent .cv-card-label {
            color: rgba(255, 255, 255, 0.9);
        }

        .cv-card-value {
            font-size: 3rem;
            font-weight: 400;
            line-height: 1;
            color: var(--cv-text);
            letter-spacing: -0.05em;
        }

        .cv-card-accent .cv-card-value,
        .cv-card-image .cv-card-value {
            color: #fff;
        }

        .cv-card-desc {
            font-size: 0.95rem;
            line-height: 1.5;
            color: #475569;
            font-weight: 400;
            margin-top: auto;
        }

        .cv-card-accent .cv-card-desc,
        .cv-card-image .cv-card-desc {
            color: rgba(255, 255, 255, 0.9);
        }

        .cv-card-bg-pattern {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            opacity: 0.6;
        }

        .cv-chip {
            position: absolute;
            background: #ffffff;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #94a3b8;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            white-space: nowrap;
        }

        .cv-card-gray .cv-card-content.push-bottom {
            margin-top: auto;
        }

        /* Marquee */
        .cv-clients-bar {
            background: var(--cv-surface);
            border-top: 1px solid var(--cv-border);
            border-bottom: 1px solid var(--cv-border);
            padding: 2.5rem 0;
            overflow: hidden;
            margin-top: 0;
        }

        .cv-marquee-container {
            width: 100%;
            overflow: hidden;
            position: relative;
            display: flex;
        }

        .cv-marquee-container::before,
        .cv-marquee-container::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 150px;
            z-index: 2;
            pointer-events: none;
        }

        .cv-marquee-container::before {
            left: 0;
            background: linear-gradient(to right, var(--cv-surface), transparent);
        }

        .cv-marquee-container::after {
            right: 0;
            background: linear-gradient(to left, var(--cv-surface), transparent);
        }

        @keyframes scrollLeft {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .cv-marquee-track {
            display: flex;
            gap: 1.5rem;
            padding: 0 1rem;
            width: max-content;
            animation: scrollLeft 30s linear infinite;
        }

        .cv-marquee-container:hover .cv-marquee-track {
            animation-play-state: paused;
        }

        .cv-client-chip {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--cv-bg);
            border: 1px solid var(--cv-border);
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--cv-text);
            white-space: nowrap;
        }

        .cv-client-chip::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--cv-accent);
            flex-shrink: 0;
        }

        /* ══════════════════════════════════════════════════════
                   PROFIL PLATFORM — 3D ORBIT EKOSISTEM HERO (REFERENCE)
                ══════════════════════════════════════════════════════ */
        .buyle-orbit-section {
            position: relative;
            padding: 8.5rem 1.5rem 5rem;
            background: radial-gradient(circle at 50% 25%, rgba(30, 179, 73, 0.08) 0%, #FAFCF8 45%, #F8FAFC 100%);
            overflow: hidden;
            border-bottom: 1px solid #E2E8F0;
        }

        .buyle-orbit-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            text-align: center;
        }

        /* Top Title with 3D Pill Badge (Reference: "The [Infinity] Team") */
        .buyle-orbit-header {
            margin-bottom: 2rem;
        }

        .buyle-orbit-title {
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.035em;
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.85rem;
            margin: 0;
            line-height: 1.2;
        }

        .buyle-orbit-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 58px;
            height: 38px;
            border-radius: 99px;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            box-shadow: 0 8px 22px rgba(30, 179, 73, 0.38), inset 0 2px 4px rgba(255, 255, 255, 0.45);
            transform: translateY(-2px);
            animation: pulseGlow 3s ease-in-out infinite;
        }

        @keyframes pulseGlow {

            0%,
            100% {
                box-shadow: 0 8px 22px rgba(30, 179, 73, 0.35), inset 0 2px 4px rgba(255, 255, 255, 0.4);
            }

            50% {
                box-shadow: 0 12px 28px rgba(30, 179, 73, 0.5), inset 0 2px 4px rgba(255, 255, 255, 0.65);
            }
        }

        /* Orbit Canvas Area */
        .buyle-orbit-canvas {
            position: relative;
            width: 100%;
            max-width: 980px;
            min-height: 580px;
            margin: 2rem auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Concentric Orbit Rings */
        .orbit-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
        }

        .orbit-ring-1 {
            width: 460px;
            height: 460px;
            border: 2px solid rgba(30, 179, 73, 0.36);
            box-shadow: 0 0 25px rgba(165, 207, 55, 0.15), inset 0 0 25px rgba(165, 207, 55, 0.08);
        }

        .orbit-ring-2 {
            width: 680px;
            height: 680px;
            border: 2px solid rgba(30, 179, 73, 0.28);
            box-shadow: 0 0 25px rgba(30, 179, 73, 0.08);
        }

        .orbit-ring-3 {
            width: 900px;
            height: 900px;
            border: 1.8px solid rgba(30, 179, 73, 0.22);
        }

        .orbit-ring-4 {
            width: 1120px;
            height: 1120px;
            border: 1.5px solid rgba(30, 179, 73, 0.16);
        }

        /* 3D Center Speech Bubble */
        .orbit-bubble-wrap {
            position: relative;
            z-index: 6;
            max-width: 490px;
            padding: 0 1rem;
        }

        .orbit-speech-bubble {
            background: linear-gradient(135deg, #1eb349 0%, #56bf3a 50%, #a5cf37 100%);
            border-radius: 32px;
            padding: 2.25rem 2.25rem 2.5rem;
            color: #ffffff;
            position: relative;
            box-shadow: 0 30px 70px -10px rgba(30, 179, 73, 0.42),
                0 10px 25px -5px rgba(0, 0, 0, 0.1),
                inset 0 1px 2px rgba(255, 255, 255, 0.45);
            text-align: left;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .orbit-speech-bubble:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 36px 75px -10px rgba(30, 179, 73, 0.52),
                inset 0 1px 2px rgba(255, 255, 255, 0.5);
        }

        .orbit-bubble-text {
            font-family: var(--font);
            margin: 0;
            line-height: 1.68;
            letter-spacing: -0.01em;
        }

        .orbit-bubble-text.primary {
            font-size: 0.95rem;
            font-weight: 500;
            color: #ffffff;
            margin-bottom: 1.25rem;
        }

        .orbit-bubble-text.primary strong {
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.18);
        }

        .orbit-bubble-text.secondary {
            font-size: 0.88rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.92);
        }

        .orbit-bubble-text.secondary strong {
            font-weight: 700;
            color: #ffffff;
        }

        /* Bubble Pointer Tail (Bottom-Left) */
        .orbit-bubble-tail {
            position: absolute;
            bottom: -14px;
            left: 36px;
            width: 28px;
            height: 28px;
            background: #1eb349;
            border-bottom-right-radius: 20px;
            clip-path: polygon(0 0, 100% 0, 0 100%);
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.15));
        }

        /* Orbiting Module Nodes */
        .orbit-nodes-group {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 7;
        }

        .orbit-node {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            pointer-events: auto;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .orbit-node-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #ffffff;
            padding: 4px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08), 0 2px 6px rgba(0, 0, 0, 0.04);
            border: 2px solid rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .orbit-node-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #16a34a;
            transition: all 0.3s ease;
        }

        .orbit-node-pill {
            background: #ffffff;
            color: #0F172A;
            font-family: var(--font);
            font-size: 0.76rem;
            font-weight: 700;
            padding: 0.35rem 0.85rem;
            border-radius: 99px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            border: 1px solid #E2E8F0;
            white-space: nowrap;
            letter-spacing: -0.01em;
            transition: all 0.3s ease;
        }

        .orbit-node:hover .orbit-node-circle {
            transform: scale(1.12);
            box-shadow: 0 16px 36px rgba(30, 179, 73, 0.25);
            border-color: #1eb349;
        }

        .orbit-node:hover .orbit-node-inner {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
        }

        .orbit-node:hover .orbit-node-pill {
            background: #0F172A;
            color: #ffffff;
            border-color: #0F172A;
            transform: translateY(2px);
        }

        /* Node Positions (Desktop) */
        .node-web-builder {
            top: 6%;
            left: 12%;
            animation: floatSlow1 4.6s ease-in-out infinite;
        }

        .node-ticket {
            bottom: 6%;
            left: 15%;
            animation: floatSlow2 5.2s ease-in-out infinite 0.6s;
        }

        .node-pos {
            top: 4%;
            right: 14%;
            animation: floatSlow1 4.8s ease-in-out infinite 1.2s;
        }

        .node-marketplace {
            top: 46%;
            right: 4%;
            animation: floatSlow2 5.4s ease-in-out infinite 0.3s;
        }

        .node-payment {
            top: 42%;
            left: 4%;
            animation: floatSlow3 4.9s ease-in-out infinite 0.9s;
        }

        /* Floating Micro-Badges */
        .orbit-badge {
            position: absolute;
            z-index: 6;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09), 0 2px 6px rgba(0, 0, 0, 0.04);
            border: 2px solid rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        .orbit-badge:hover {
            transform: scale(1.15) rotate(6deg);
        }

        .badge-rocket {
            top: 18%;
            left: 28%;
            animation: floatSlow2 4.2s ease-in-out infinite 0.4s;
        }

        .badge-shield {
            top: 16%;
            right: 28%;
            animation: floatSlow3 4.5s ease-in-out infinite 1.1s;
        }

        .badge-brush {
            bottom: 18%;
            right: 26%;
            animation: floatSlow1 4.4s ease-in-out infinite 0.8s;
        }

        /* Bottom Pill (Reference: Headquartered in ...) */
        .orbit-bottom-pill {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 7;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            font-family: var(--font);
            font-size: 0.82rem;
            font-weight: 700;
            padding: 0.65rem 1.6rem;
            border-radius: 99px;
            box-shadow: 0 10px 28px rgba(30, 179, 73, 0.35), inset 0 1px 2px rgba(255, 255, 255, 0.35);
            letter-spacing: 0.01em;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .orbit-bottom-pill:hover {
            transform: translateX(-50%) translateY(-2px);
            box-shadow: 0 14px 34px rgba(30, 179, 73, 0.48);
        }

        .orbit-bottom-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #a5cf37;
            box-shadow: 0 0 8px #a5cf37;
        }

        /* Float Animations */
        @keyframes floatSlow1 {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-9px) rotate(1.5deg);
            }
        }

        @keyframes floatSlow2 {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(8px) rotate(-1.5deg);
            }
        }

        @keyframes floatSlow3 {

            0%,
            100% {
                transform: translateY(0px) translateX(0px);
            }

            50% {
                transform: translateY(-7px) translateX(4px);
            }
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .buyle-orbit-canvas {
                min-height: 520px;
                max-width: 820px;
            }

            .node-web-builder {
                top: 4%;
                left: 8%;
            }

            .node-ticket {
                bottom: 4%;
                left: 10%;
            }

            .node-pos {
                top: 2%;
                right: 10%;
            }

            .node-marketplace {
                top: 44%;
                right: 2%;
            }

            .node-payment {
                top: 40%;
                left: 2%;
            }

            .badge-rocket {
                left: 24%;
            }

            .badge-shield {
                right: 24%;
            }

            .badge-brush {
                right: 22%;
            }
        }

        @media (max-width: 768px) {
            .buyle-orbit-section {
                padding: 6.5rem 1rem 4rem;
            }

            .buyle-orbit-canvas {
                min-height: auto;
                display: flex;
                flex-direction: column;
                gap: 2rem;
                padding-bottom: 2rem;
            }

            .orbit-ring {
                display: none;
            }

            .orbit-bubble-wrap {
                max-width: 100%;
                order: 1;
                padding: 0;
            }

            .orbit-speech-bubble {
                padding: 1.75rem 1.5rem 2rem;
                border-radius: 24px;
            }

            .orbit-bubble-tail {
                bottom: -10px;
                left: 28px;
                width: 20px;
                height: 20px;
            }

            /* On mobile, turn nodes group into responsive cards grid below the bubble */
            .orbit-nodes-group {
                position: static !important;
                display: grid !important;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)) !important;
                gap: 0.85rem !important;
                width: 100% !important;
                order: 2;
                margin-top: 0.5rem;
            }

            .orbit-node {
                position: static !important;
                animation: none !important;
                flex-direction: row !important;
                gap: 0.85rem !important;
                background: #ffffff !important;
                padding: 0.75rem 1rem !important;
                border-radius: 16px !important;
                border: 1.5px solid #E2E8F0 !important;
                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04) !important;
                width: 100% !important;
                justify-content: flex-start !important;
                text-align: left !important;
            }

            .orbit-node-circle {
                width: 48px !important;
                height: 48px !important;
                padding: 3px !important;
                box-shadow: none !important;
                flex-shrink: 0 !important;
            }

            .orbit-node-pill {
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                font-size: 0.9rem !important;
                text-align: left !important;
            }

            .orbit-badge {
                display: none !important;
            }

            .orbit-bottom-pill {
                position: static !important;
                transform: none !important;
                order: 3;
                margin-top: 1rem;
                width: 100%;
                justify-content: center;
                white-space: normal;
                text-align: center;
                font-size: 0.82rem;
                padding: 0.75rem 1.25rem;
            }
        }

        /* ════ HOW IT WORKS (Cara Jadi Creator & Pembeli) ════ */
        .buyle-flow-section {
            background: #ffffff;
            padding: 6rem 1.5rem;
            position: relative;
            overflow: hidden;
            border-top: 1px solid #F1F5F9;
        }

        .buyle-flow-container {
            max-width: 1240px;
            margin: 0 auto;
            position: relative;
        }

        /* Top Header */
        .buyle-flow-header {
            text-align: center;
            max-width: 760px;
            margin: 0 auto 3.5rem;
        }

        .buyle-flow-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #16a34a;
            background: #f0fdf4;
            padding: 0.4rem 1rem;
            border-radius: 99px;
            border: 1px solid rgba(30, 179, 73, 0.25);
            margin-bottom: 1rem;
        }

        .buyle-flow-title {
            font-size: clamp(2rem, 3.8vw, 2.75rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.22;
            letter-spacing: -0.025em;
            margin: 0 0 1rem;
        }

        .buyle-flow-title .gradient-text {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .buyle-flow-desc {
            font-size: 1.05rem;
            color: #64748B;
            line-height: 1.65;
            margin: 0 0 2rem;
        }

        /* Interactive Switch Tabs (Navbar Button Gradient Style) */
        .buyle-flow-switch-wrap {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .buyle-flow-switch {
            position: relative;
            display: inline-flex;
            background: #F1F5F9;
            padding: 5px;
            border-radius: 999px;
            border: 1px solid #E2E8F0;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.04);
            user-select: none;
        }

        .buyle-switch-tab {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.75rem 1.6rem;
            border-radius: 999px;
            border: none;
            background: transparent;
            font-family: var(--font);
            font-size: 0.92rem;
            font-weight: 700;
            color: #64748B;
            cursor: pointer;
            transition: color 0.3s ease;
            outline: none;
            white-space: nowrap;
        }

        .buyle-switch-tab.active {
            color: #ffffff;
        }

        .buyle-switch-tab svg {
            transition: transform 0.3s ease;
        }

        .buyle-switch-tab:hover svg {
            transform: scale(1.15);
        }

        .buyle-switch-glider {
            position: absolute;
            top: 5px;
            left: 5px;
            height: calc(100% - 10px);
            width: calc(50% - 5px);
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            border-radius: 999px;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
            z-index: 1;
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .buyle-flow-switch.show-buyer .buyle-switch-glider {
            transform: translateX(100%);
        }

        /* Flow Panels Container */
        .buyle-flow-panels {
            position: relative;
            width: 100%;
        }

        .buyle-flow-panel {
            display: none;
            opacity: 0;
            transition: opacity 0.4s ease, transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            transform: translateY(12px);
        }

        .buyle-flow-panel.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* Desktop Canvas Layout (Matches Reference Image) */
        .buyle-flow-canvas {
            position: relative;
            width: 100%;
            max-width: 1140px;
            min-height: 680px;
            margin: 2rem auto 0;
            display: flex;
            align-items: center;
        }

        /* Central Hub Circle (Left) */
        .buyle-flow-hub-wrap {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 440px;
            height: 440px;
            z-index: 4;
        }

        .buyle-flow-hub-card {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 25px 65px -10px rgba(0, 0, 0, 0.08), 0 4px 16px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem 2.5rem;
            position: relative;
            box-sizing: border-box;
        }

        /* Semicircular Gradient Arc around Hub (Reference visual) */
        .buyle-flow-hub-arc {
            position: absolute;
            inset: -22px;
            width: calc(100% + 44px);
            height: calc(100% + 44px);
            pointer-events: none;
            z-index: 1;
        }

        .buyle-hub-subtitle-top {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #16a34a;
            margin-bottom: 0.5rem;
        }

        .buyle-hub-main-title {
            font-size: clamp(1.85rem, 2.8vw, 2.35rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.18;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .buyle-hub-main-title span.accent {
            display: block;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .buyle-hub-accent-bar {
            width: 44px;
            height: 3.5px;
            border-radius: 99px;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            margin: 1.25rem auto 1rem;
        }

        .buyle-hub-desc {
            font-size: 0.92rem;
            color: #64748B;
            line-height: 1.6;
            margin: 0;
            max-width: 290px;
        }

        /* Curved Ribbon Track (SVG background layer) */
        .buyle-flow-track-layer {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 2;
        }

        /* Right Steps Arc Group */
        .buyle-flow-nodes {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 5;
        }

        .buyle-flow-step {
            position: absolute;
            display: flex;
            align-items: center;
            gap: 1rem;
            pointer-events: auto;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .buyle-flow-step:hover {
            transform: translateX(6px);
        }

        /* Numbered Node Button (01-05 Inside the Green Semi-Circle Ribbon) */
        .buyle-flow-node-num {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: #ffffff;
            border: 2.5px solid rgba(30, 179, 73, 0.4);
            box-shadow: 0 8px 22px rgba(30, 179, 73, 0.22), 0 2px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font);
            font-size: 1.25rem;
            font-weight: 800;
            color: #1eb349;
            flex-shrink: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            z-index: 6;
        }

        .buyle-flow-node-num span {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .buyle-flow-step:hover .buyle-flow-node-num {
            transform: scale(1.12);
            box-shadow: 0 12px 28px rgba(30, 179, 73, 0.35);
            border-color: #1eb349;
        }

        /* Pointer Dashed Line with Arrow */
        .buyle-flow-pointer {
            display: flex;
            align-items: center;
            width: 36px;
            position: relative;
            flex-shrink: 0;
        }

        .buyle-flow-pointer-line {
            width: 100%;
            height: 1.5px;
            border-top: 1.5px dashed rgba(30, 179, 73, 0.55);
        }

        .buyle-flow-pointer-arrow {
            color: #1eb349;
            margin-left: -5px;
            display: flex;
            align-items: center;
        }

        /* Step Card Info (Inline Icon + Title + 2-Line Desc) */
        .buyle-flow-card {
            width: 330px;
            text-align: left;
            transition: transform 0.3s ease;
        }

        .buyle-flow-card-head {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            margin-bottom: 0.3rem;
        }

        .buyle-flow-card-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid rgba(30, 179, 73, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #16a34a;
            flex-shrink: 0;
            box-shadow: 0 3px 8px rgba(30, 179, 73, 0.08);
            transition: all 0.3s ease;
        }

        .buyle-flow-step:hover .buyle-flow-card-icon {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            transform: scale(1.08) rotate(4deg);
            box-shadow: 0 6px 14px rgba(30, 179, 73, 0.28);
        }

        .buyle-flow-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0F172A;
            margin: 0;
            letter-spacing: -0.01em;
            transition: color 0.3s ease;
        }

        .buyle-flow-step:hover .buyle-flow-card-title {
            color: #16a34a;
        }

        /* Enforce strictly 2 lines for descriptions */
        .buyle-flow-card-desc {
            font-size: 0.85rem;
            color: #64748B;
            line-height: 1.45;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── Mathematical Node Coordinates (Centered on the 86px-wide Curved Ribbon) ── */
        .step-pos-1 {
            top: 37px;
            left: 396px;
        }

        .step-pos-2 {
            top: 156px;
            left: 502px;
        }

        .step-pos-3 {
            top: 311px;
            left: 541px;
        }

        .step-pos-4 {
            top: 466px;
            left: 502px;
        }

        .step-pos-5 {
            top: 585px;
            left: 396px;
        }

        /* ── 4-Second Interactive In-Animation System (Smooth Motion) ── */
        .buyle-flow-panel:not(.active) .buyle-flow-hub-card,
        .buyle-flow-panel:not(.active) .buyle-flow-step,
        .buyle-flow-panel:not(.active) .buyle-mobile-step-item {
            opacity: 0;
        }

        .buyle-flow-panel.active:not(.animating-in) .buyle-flow-hub-card,
        .buyle-flow-panel.active:not(.animating-in) .buyle-flow-step,
        .buyle-flow-panel.active:not(.animating-in) .buyle-mobile-step-item {
            opacity: 1;
        }

        .buyle-flow-panel.animating-in .buyle-flow-hub-card {
            animation: hubCardEnter 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .buyle-flow-panel.animating-in .buyle-flow-track-glow {
            animation: flowTrackDraw 3.8s cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }

        .buyle-flow-panel.animating-in .buyle-flow-step.step-pos-1 {
            animation: stepEnter4s 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.15s both;
        }
        .buyle-flow-panel.animating-in .buyle-flow-step.step-pos-2 {
            animation: stepEnter4s 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.85s both;
        }
        .buyle-flow-panel.animating-in .buyle-flow-step.step-pos-3 {
            animation: stepEnter4s 0.8s cubic-bezier(0.16, 1, 0.3, 1) 1.55s both;
        }
        .buyle-flow-panel.animating-in .buyle-flow-step.step-pos-4 {
            animation: stepEnter4s 0.8s cubic-bezier(0.16, 1, 0.3, 1) 2.25s both;
        }
        .buyle-flow-panel.animating-in .buyle-flow-step.step-pos-5 {
            animation: stepEnter4s 0.8s cubic-bezier(0.16, 1, 0.3, 1) 2.95s both;
        }

        .buyle-flow-panel.animating-in .step-pos-1 .buyle-flow-pointer {
            animation: pointerDraw4s 0.6s ease 0.35s both;
        }
        .buyle-flow-panel.animating-in .step-pos-2 .buyle-flow-pointer {
            animation: pointerDraw4s 0.6s ease 1.05s both;
        }
        .buyle-flow-panel.animating-in .step-pos-3 .buyle-flow-pointer {
            animation: pointerDraw4s 0.6s ease 1.75s both;
        }
        .buyle-flow-panel.animating-in .step-pos-4 .buyle-flow-pointer {
            animation: pointerDraw4s 0.6s ease 2.45s both;
        }
        .buyle-flow-panel.animating-in .step-pos-5 .buyle-flow-pointer {
            animation: pointerDraw4s 0.6s ease 3.15s both;
        }

        .buyle-flow-panel.animating-in .step-pos-1 .buyle-flow-card {
            animation: cardSlide4s 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.45s both;
        }
        .buyle-flow-panel.animating-in .step-pos-2 .buyle-flow-card {
            animation: cardSlide4s 0.7s cubic-bezier(0.16, 1, 0.3, 1) 1.15s both;
        }
        .buyle-flow-panel.animating-in .step-pos-3 .buyle-flow-card {
            animation: cardSlide4s 0.7s cubic-bezier(0.16, 1, 0.3, 1) 1.85s both;
        }
        .buyle-flow-panel.animating-in .step-pos-4 .buyle-flow-card {
            animation: cardSlide4s 0.7s cubic-bezier(0.16, 1, 0.3, 1) 2.55s both;
        }
        .buyle-flow-panel.animating-in .step-pos-5 .buyle-flow-card {
            animation: cardSlide4s 0.7s cubic-bezier(0.16, 1, 0.3, 1) 3.25s both;
        }

        /* Mobile Timeline items staggering across 4 seconds */
        .buyle-flow-panel.animating-in .buyle-mobile-step-item:nth-child(1) {
            animation: mobileItem4s 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
        }
        .buyle-flow-panel.animating-in .buyle-mobile-step-item:nth-child(2) {
            animation: mobileItem4s 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.9s both;
        }
        .buyle-flow-panel.animating-in .buyle-mobile-step-item:nth-child(3) {
            animation: mobileItem4s 0.7s cubic-bezier(0.16, 1, 0.3, 1) 1.6s both;
        }
        .buyle-flow-panel.animating-in .buyle-mobile-step-item:nth-child(4) {
            animation: mobileItem4s 0.7s cubic-bezier(0.16, 1, 0.3, 1) 2.3s both;
        }
        .buyle-flow-panel.animating-in .buyle-mobile-step-item:nth-child(5) {
            animation: mobileItem4s 0.7s cubic-bezier(0.16, 1, 0.3, 1) 3.0s both;
        }

        @keyframes flowTrackDraw {
            0% {
                stroke-dashoffset: 750;
                opacity: 0.2;
            }
            20% {
                opacity: 1;
            }
            85% {
                stroke-dashoffset: 0;
                opacity: 0.9;
            }
            100% {
                stroke-dashoffset: 0;
                opacity: 0.7;
            }
        }

        @keyframes hubCardEnter {
            0% {
                opacity: 0;
                transform: scale(0.85);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes stepEnter4s {
            0% {
                opacity: 0;
                transform: scale(0.35);
            }
            60% {
                opacity: 1;
                transform: scale(1.1);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes pointerDraw4s {
            0% {
                opacity: 0;
                transform: scaleX(0);
                transform-origin: left center;
            }
            100% {
                opacity: 1;
                transform: scaleX(1);
                transform-origin: left center;
            }
        }

        @keyframes cardSlide4s {
            0% {
                opacity: 0;
                transform: translateX(-18px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes mobileItem4s {
            0% {
                opacity: 0;
                transform: translateY(22px) scale(0.96);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ── Mobile & Tablet Layout (max-width: 991px) ── */
        .buyle-flow-mobile-view {
            display: none;
        }

        @media(max-width: 991px) {
            .buyle-flow-section {
                padding: 4rem 1rem;
            }

            .buyle-flow-canvas {
                display: none !important;
            }

            .buyle-flow-mobile-view {
                display: block;
                width: 100%;
                max-width: 580px;
                margin: 2rem auto 0;
            }

            .buyle-mobile-hub {
                background: #ffffff;
                border-radius: 24px;
                border: 1.5px solid #E2E8F0;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                padding: 2rem 1.5rem;
                text-align: center;
                margin-bottom: 2.5rem;
                position: relative;
                overflow: hidden;
            }

            .buyle-mobile-hub::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 5px;
                background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            }

            .buyle-mobile-timeline {
                position: relative;
                padding-left: 20px;
            }

            .buyle-mobile-timeline::before {
                content: '';
                position: absolute;
                left: 45px;
                top: 24px;
                bottom: 24px;
                width: 3px;
                background: linear-gradient(180deg, #1eb349 0%, #a5cf37 100%);
                border-radius: 99px;
            }

            .buyle-mobile-step-item {
                display: flex;
                gap: 1.25rem;
                align-items: flex-start;
                margin-bottom: 1.5rem;
                position: relative;
                z-index: 2;
                transition: transform 0.3s ease;
            }

            .buyle-mobile-node-num {
                width: 52px;
                height: 52px;
                border-radius: 50%;
                background: #ffffff;
                border: 2px solid #1eb349;
                box-shadow: 0 6px 18px rgba(30, 179, 73, 0.22);
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: var(--font);
                font-size: 1.2rem;
                font-weight: 800;
                flex-shrink: 0;
            }

            .buyle-mobile-node-num span {
                background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .buyle-mobile-card-body {
                background: #ffffff;
                border: 1.5px solid #E2E8F0;
                border-radius: 20px;
                padding: 1.25rem 1.25rem;
                flex-grow: 1;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            }

            .buyle-mobile-card-top {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 0.4rem;
            }

            .buyle-mobile-card-icon {
                width: 34px;
                height: 34px;
                border-radius: 9px;
                background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
                color: #16a34a;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .buyle-mobile-card-title {
                font-size: 1.05rem;
                font-weight: 700;
                color: #0F172A;
                margin: 0;
            }

            .buyle-mobile-card-desc {
                font-size: 0.85rem;
                color: #64748B;
                line-height: 1.45;
                margin: 0;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
    </style>

    {{-- ════ 1. PROFIL PLATFORM (Orbit Ekosistem Buyle.id) ════ --}}
    <section class="buyle-orbit-section" id="profil-platform">
        <div class="buyle-orbit-container">

            {{-- Breadcrumb --}}
            <nav class="sv-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route_locale('home') }}">Beranda</a>
                <span class="sv-breadcrumb-sep">/</span>
                <span class="sv-breadcrumb-current">Tentang Kami</span>
            </nav>

            {{-- Title Header (Reference: "The [Infinity] Team" -> "Profil [Icon] Platform") --}}
            <div class="buyle-orbit-header" data-aos="fade-down" data-aos-duration="800">
                <h1 class="buyle-orbit-title">
                    Apa itu
                    <span class="buyle-orbit-pill" title="Ekosistem Terintegrasi">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 12c-2-2.5-4-4-6.5-4a4.5 4.5 0 0 0 0 9c2.5 0 4.5-1.5 6.5-4 2 2.5 4 4 6.5 4a4.5 4.5 0 0 0 0-9c-2.5 0-4.5 1.5-6.5 4Z"/>
                        </svg>
                    </span>
                    BUYLE.ID
                </h1>
            </div>

            {{-- Orbit Visual Canvas --}}
            <div class="buyle-orbit-canvas" data-aos="zoom-in" data-aos-duration="1000">

                {{-- Concentric Orbital Rings --}}
                <div class="orbit-ring orbit-ring-1"></div>
                <div class="orbit-ring orbit-ring-2"></div>
                <div class="orbit-ring orbit-ring-3"></div>
                <div class="orbit-ring orbit-ring-4"></div>

                {{-- Central 3D Speech Bubble (Signature Buyle Green Theme) --}}
                <div class="orbit-bubble-wrap" data-aos="fade-up" data-aos-delay="200">
                    <div class="orbit-speech-bubble">
                        <p class="orbit-bubble-text primary">
                            Kami menyatukan <strong>Web Builder</strong>, <strong>Kasir POS</strong>, <strong>Ticket
                                Management</strong>, <strong>Marketplace Digital</strong>, dan <strong>Pembayaran
                                Terintegrasi</strong> ke dalam satu platform tunggal - dirancang untuk memaksimalkan
                            efisiensi, pendapatan, dan kemudahan bisnis Anda.
                        </p>
                        <p class="orbit-bubble-text secondary">
                            Dibangun oleh HVM Digital untuk Indonesia, <strong>Buyle.id</strong> hadir menjawab kebutuhan
                            ekosistem modern: kendali penuh tanpa koding, otomasi cerdas, dan solusi terintegrasi yang
                            saling terhubung.
                        </p>
                        {{-- Bubble Pointer Tail --}}
                        <div class="orbit-bubble-tail"></div>
                    </div>
                </div>

                {{-- Orbiting Module Nodes Group --}}
                <div class="orbit-nodes-group">

                    {{-- Module Node 1: Web Builder (Top-Left) --}}
                    <div class="orbit-node node-web-builder" data-aos="fade-down-right" data-aos-delay="300">
                        <div class="orbit-node-circle">
                            <div class="orbit-node-inner">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                    <line x1="3" y1="9" x2="21" y2="9" />
                                    <line x1="9" y1="21" x2="9" y2="9" />
                                </svg>
                            </div>
                        </div>
                        <div class="orbit-node-pill">Web Builder</div>
                    </div>

                    {{-- Module Node 2: Ticket System Management (Bottom-Left) --}}
                    <div class="orbit-node node-ticket" data-aos="fade-up-right" data-aos-delay="400">
                        <div class="orbit-node-circle">
                            <div class="orbit-node-inner">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" />
                                    <line x1="13" y1="5" x2="13" y2="7" />
                                    <line x1="13" y1="11" x2="13" y2="13" />
                                    <line x1="13" y1="17" x2="13" y2="19" />
                                </svg>
                            </div>
                        </div>
                        <div class="orbit-node-pill">Ticket Management</div>
                    </div>

                    {{-- Module Node 3: Kasir POS (Top-Right) --}}
                    <div class="orbit-node node-pos" data-aos="fade-down-left" data-aos-delay="350">
                        <div class="orbit-node-circle">
                            <div class="orbit-node-inner">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="2" y="3" width="20" height="14" rx="2" />
                                    <line x1="8" y1="21" x2="16" y2="21" />
                                    <line x1="12" y1="17" x2="12" y2="21" />
                                    <line x1="6" y1="8" x2="18" y2="8" />
                                    <line x1="6" y1="12" x2="12" y2="12" />
                                </svg>
                            </div>
                        </div>
                        <div class="orbit-node-pill">Kasir POS</div>
                    </div>

                    {{-- Module Node 4: Marketplace Produk & Jasa Digital (Right) --}}
                    <div class="orbit-node node-marketplace" data-aos="fade-left" data-aos-delay="450">
                        <div class="orbit-node-circle">
                            <div class="orbit-node-inner">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6l-3-4z" />
                                    <line x1="3" y1="6" x2="21" y2="6" />
                                    <path d="M16 10a4 4 0 0 1-8 0" />
                                </svg>
                            </div>
                        </div>
                        <div class="orbit-node-pill">Marketplace Produk & Jasa</div>
                    </div>

                    {{-- Module Node 5: Pembayaran Terintegrasi (Left) --}}
                    <div class="orbit-node node-payment" data-aos="fade-right" data-aos-delay="250">
                        <div class="orbit-node-circle">
                            <div class="orbit-node-inner">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="1" y="4" width="22" height="16" rx="2" />
                                    <line x1="1" y1="10" x2="23" y2="10" />
                                    <path d="M6 15h4" />
                                    <path d="M15 15h3" />
                                </svg>
                            </div>
                        </div>
                        <div class="orbit-node-pill">Pembayaran Terpadu</div>
                    </div>

                </div>

                {{-- Floating Micro-Badges (Matches Rocket, Shield, Paintbrush in reference) --}}
                <div class="orbit-badge badge-rocket" title="Kecepatan & Performa Tinggi">
                    <svg width="18" height="18" fill="none" stroke="#8b5cf6" stroke-width="2.2" viewBox="0 0 24 24">
                        <path
                            d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z" />
                        <path
                            d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z" />
                    </svg>
                </div>

                <div class="orbit-badge badge-shield" title="Keamanan Transaksi & Escrow">
                    <svg width="18" height="18" fill="#f97316" viewBox="0 0 24 24">
                        <path
                            d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                    </svg>
                </div>

                <div class="orbit-badge badge-brush" title="Kustomisasi Desain Tanpa Batas">
                    <svg width="18" height="18" fill="none" stroke="#0284c7" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="m18.37 2.63 3 3L13.41 13.6a2 2 0 0 1-.88.51l-3.96.99.99-3.96a2 2 0 0 1 .51-.88z" />
                        <path d="M14.5 7.5 16.5 9.5" />
                    </svg>
                </div>

                {{-- Bottom Pill (Reference: "Headquartered in Walnut Creek, CA") --}}
                <div class="orbit-bottom-pill" data-aos="fade-up" data-aos-delay="500">
                    <span class="orbit-bottom-dot"></span>
                    🇮🇩 Karya Anak Bangsa • Ekosistem Digital All-in-One Terintegrasi
                </div>

            </div>

    </section>

    {{-- ════ 2. PANDUAN LANGKAH: CARA JADI CREATOR & CARA BELANJA ════ --}}
    <section class="buyle-flow-section" id="buyle-how-it-works">
        <div class="buyle-flow-container">

            {{-- Section Header --}}
            <div class="buyle-flow-header">

                <h2 class="buyle-flow-title">
                    Satu Ekosistem, <span class="gradient-text">Dua Cara Menikmati Kemudahannya</span>
                </h2>
                <p class="buyle-flow-desc">
                    Pelajari langkah mudah meluncurkan toko digital karya Anda, atau temukan ribuan produk digital siap pakai dalam 5 langkah sederhana.
                </p>

                {{-- Interactive Switch Tabs (Navbar Button Gradient) --}}
                <div class="buyle-flow-switch-wrap">
                    <div class="buyle-flow-switch" id="flowSwitchBox">
                        <button type="button" class="buyle-switch-tab active" data-flow="creator" id="tabFlowCreator" aria-label="Tampilkan panduan untuk Creator">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                            </svg>
                            <span>Sebagai Creator</span>
                        </button>
                        <button type="button" class="buyle-switch-tab" data-flow="buyer" id="tabFlowBuyer" aria-label="Tampilkan panduan untuk Pembeli">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            <span>Sebagai Pembeli</span>
                        </button>
                        <span class="buyle-switch-glider"></span>
                    </div>
                </div>
            </div>

            {{-- ──────────────── PANEL 1: SEBAGAI CREATOR ──────────────── --}}
            <div class="buyle-flow-panel panel-creator active animating-in" id="flowPanelCreator">

                {{-- Desktop View --}}
                <div class="buyle-flow-canvas">
                    {{-- SVG Background Track Layer (Matching Reference Image Arc) --}}
                    <svg class="buyle-flow-track-layer" viewBox="0 0 1140 680" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="flowTrackGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#1eb349" />
                                <stop offset="100%" stop-color="#a5cf37" />
                            </linearGradient>
                        </defs>

                        {{-- Hub Surrounding Decorative Rings --}}
                        <circle cx="240" cy="340" r="232" stroke="rgba(30, 179, 73, 0.16)" stroke-width="1.5" />
                        <circle cx="240" cy="340" r="255" stroke="rgba(30, 179, 73, 0.09)" stroke-width="1" stroke-dasharray="4 6" />
                        <path d="M 130 180 A 225 225 0 0 1 350 180" stroke="url(#flowTrackGrad)" stroke-width="5" stroke-linecap="round" />
                        <path d="M 130 500 A 225 225 0 0 0 350 500" stroke="url(#flowTrackGrad)" stroke-width="5" stroke-linecap="round" />

                        {{-- Thick Green Semicircle Ribbon Track (The "Setengah Lingkaran" Arc) --}}
                        <path d="M 395 49 A 330 330 0 0 1 570 340 A 330 330 0 0 1 395 631" stroke="#EBF8ED" stroke-width="84" stroke-linecap="round" />
                        <path d="M 395 49 A 330 330 0 0 1 570 340 A 330 330 0 0 1 395 631" stroke="rgba(30, 179, 73, 0.22)" stroke-width="86" stroke-linecap="round" />
                        <path d="M 395 49 A 330 330 0 0 1 570 340 A 330 330 0 0 1 395 631" stroke="rgba(30, 179, 73, 0.45)" stroke-width="2" stroke-linecap="round" stroke-dasharray="6 8" />

                        {{-- Smooth 4s Glowing Animated Beam --}}
                        <path class="buyle-flow-track-glow" d="M 395 49 A 330 330 0 0 1 570 340 A 330 330 0 0 1 395 631" stroke="url(#flowTrackGrad)" stroke-width="5" stroke-linecap="round" stroke-dasharray="750" stroke-dashoffset="750" />
                    </svg>

                    {{-- Left Central Hub Circle --}}
                    <div class="buyle-flow-hub-wrap">
                        <div class="buyle-flow-hub-card">
                            <div class="buyle-hub-subtitle-top">PANDUAN KREATOR</div>
                            <h3 class="buyle-hub-main-title">
                                5 Langkah
                                <span class="accent">Sukses Creator</span>
                                di Buyle.id
                            </h3>
                            <div class="buyle-hub-accent-bar"></div>
                            <p class="buyle-hub-desc">
                                Apa yang membuat toko Anda dipercaya dan menghasilkan pendapatan konsisten?
                            </p>
                        </div>
                    </div>

                    {{-- Right Steps Arc Group (5 Nodes Centered INSIDE the Ribbon with Connectors and Cards) --}}
                    <div class="buyle-flow-nodes">
                        {{-- Step 01 --}}
                        <div class="buyle-flow-step step-pos-1">
                            <div class="buyle-flow-node-num">
                                <span>01</span>
                            </div>
                            <div class="buyle-flow-pointer">
                                <div class="buyle-flow-pointer-line"></div>
                                <div class="buyle-flow-pointer-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </div>
                            <div class="buyle-flow-card">
                                <div class="buyle-flow-card-head">
                                    <div class="buyle-flow-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <line x1="19" y1="8" x2="19" y2="14"></line>
                                            <line x1="22" y1="11" x2="16" y2="11"></line>
                                        </svg>
                                    </div>
                                    <h4 class="buyle-flow-card-title">Daftar Akun Kreator</h4>
                                </div>
                                <p class="buyle-flow-card-desc">Registrasi cepat & gratis. Dapatkan URL toko eksklusif buyle.id/nama-anda.</p>
                            </div>
                        </div>

                        {{-- Step 02 --}}
                        <div class="buyle-flow-step step-pos-2">
                            <div class="buyle-flow-node-num">
                                <span>02</span>
                            </div>
                            <div class="buyle-flow-pointer">
                                <div class="buyle-flow-pointer-line"></div>
                                <div class="buyle-flow-pointer-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </div>
                            <div class="buyle-flow-card">
                                <div class="buyle-flow-card-head">
                                    <div class="buyle-flow-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83M16.62 12l-5.74 9.94"></path>
                                        </svg>
                                    </div>
                                    <h4 class="buyle-flow-card-title">Rancang Web & Bio Link</h4>
                                </div>
                                <p class="buyle-flow-card-desc">Rancang storefront, landing page, dan bio link estetik via drag-and-drop builder.</p>
                            </div>
                        </div>

                        {{-- Step 03 --}}
                        <div class="buyle-flow-step step-pos-3">
                            <div class="buyle-flow-node-num">
                                <span>03</span>
                            </div>
                            <div class="buyle-flow-pointer">
                                <div class="buyle-flow-pointer-line"></div>
                                <div class="buyle-flow-pointer-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </div>
                            <div class="buyle-flow-card">
                                <div class="buyle-flow-card-head">
                                    <div class="buyle-flow-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                        </svg>
                                    </div>
                                    <h4 class="buyle-flow-card-title">Upload Produk & Jasa</h4>
                                </div>
                                <p class="buyle-flow-card-desc">Unggah karya digital, tiket event, lisensi, atau buka konsultasi online Anda.</p>
                            </div>
                        </div>

                        {{-- Step 04 --}}
                        <div class="buyle-flow-step step-pos-4">
                            <div class="buyle-flow-node-num">
                                <span>04</span>
                            </div>
                            <div class="buyle-flow-pointer">
                                <div class="buyle-flow-pointer-line"></div>
                                <div class="buyle-flow-pointer-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </div>
                            <div class="buyle-flow-card">
                                <div class="buyle-flow-card-head">
                                    <div class="buyle-flow-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                            <polyline points="9 12 11 14 15 10"></polyline>
                                        </svg>
                                    </div>
                                    <h4 class="buyle-flow-card-title">Pembayaran Terintegrasi</h4>
                                </div>
                                <p class="buyle-flow-card-desc">Terima QRIS, Virtual Account, & Kartu Kredit instan dengan verifikasi otomatis.</p>
                            </div>
                        </div>

                        {{-- Step 05 --}}
                        <div class="buyle-flow-step step-pos-5">
                            <div class="buyle-flow-node-num">
                                <span>05</span>
                            </div>
                            <div class="buyle-flow-pointer">
                                <div class="buyle-flow-pointer-line"></div>
                                <div class="buyle-flow-pointer-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </div>
                            <div class="buyle-flow-card">
                                <div class="buyle-flow-card-head">
                                    <div class="buyle-flow-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <line x1="12" y1="1" x2="12" y2="23"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                    </div>
                                    <h4 class="buyle-flow-card-title">Promosi & Payout Cepat</h4>
                                </div>
                                <p class="buyle-flow-card-desc">Jangkau pembeli lewat Meta Automation dan cairkan penghasilan kapan saja.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mobile Timeline View --}}
                <div class="buyle-flow-mobile-view">
                    <div class="buyle-mobile-hub">
                        <div class="buyle-hub-subtitle-top">PANDUAN KREATOR</div>
                        <h3 class="buyle-hub-main-title" style="font-size: 1.6rem;">
                            5 Langkah <span class="accent">Sukses Creator</span> di Buyle.id
                        </h3>
                        <div class="buyle-hub-accent-bar"></div>
                        <p class="buyle-hub-desc" style="max-width: 100%;">
                            Ubah keahlian dan karya digital Anda menjadi sumber pendapatan pasif yang berkelanjutan.
                        </p>
                    </div>

                    <div class="buyle-mobile-timeline">
                        <div class="buyle-mobile-step-item">
                            <div class="buyle-mobile-node-num"><span>01</span></div>
                            <div class="buyle-mobile-card-body">
                                <div class="buyle-mobile-card-top">
                                    <div class="buyle-mobile-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                                    </div>
                                    <h4 class="buyle-mobile-card-title">Daftar Akun Kreator</h4>
                                </div>
                                <p class="buyle-mobile-card-desc">Registrasi cepat & gratis. Dapatkan URL toko eksklusif buyle.id/nama-anda.</p>
                            </div>
                        </div>

                        <div class="buyle-mobile-step-item">
                            <div class="buyle-mobile-node-num"><span>02</span></div>
                            <div class="buyle-mobile-card-body">
                                <div class="buyle-mobile-card-top">
                                    <div class="buyle-mobile-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83M16.62 12l-5.74 9.94"></path></svg>
                                    </div>
                                    <h4 class="buyle-mobile-card-title">Rancang Web & Bio Link</h4>
                                </div>
                                <p class="buyle-mobile-card-desc">Rancang storefront, landing page, dan bio link estetik via drag-and-drop builder.</p>
                            </div>
                        </div>

                        <div class="buyle-mobile-step-item">
                            <div class="buyle-mobile-node-num"><span>03</span></div>
                            <div class="buyle-mobile-card-body">
                                <div class="buyle-mobile-card-top">
                                    <div class="buyle-mobile-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                    </div>
                                    <h4 class="buyle-mobile-card-title">Upload Produk & Jasa</h4>
                                </div>
                                <p class="buyle-mobile-card-desc">Unggah karya digital, tiket event, lisensi, atau buka konsultasi online Anda.</p>
                            </div>
                        </div>

                        <div class="buyle-mobile-step-item">
                            <div class="buyle-mobile-node-num"><span>04</span></div>
                            <div class="buyle-mobile-card-body">
                                <div class="buyle-mobile-card-top">
                                    <div class="buyle-mobile-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>
                                    </div>
                                    <h4 class="buyle-mobile-card-title">Pembayaran Terintegrasi</h4>
                                </div>
                                <p class="buyle-mobile-card-desc">Terima QRIS, Virtual Account, & Kartu Kredit instan dengan verifikasi otomatis.</p>
                            </div>
                        </div>

                        <div class="buyle-mobile-step-item">
                            <div class="buyle-mobile-node-num"><span>05</span></div>
                            <div class="buyle-mobile-card-body">
                                <div class="buyle-mobile-card-top">
                                    <div class="buyle-mobile-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                    </div>
                                    <h4 class="buyle-mobile-card-title">Promosi & Payout Cepat</h4>
                                </div>
                                <p class="buyle-mobile-card-desc">Jangkau pembeli lewat Meta Automation dan cairkan penghasilan kapan saja.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            {{-- ──────────────── PANEL 2: SEBAGAI PEMBELI ──────────────── --}}
            <div class="buyle-flow-panel panel-buyer animating-in" id="flowPanelBuyer">

                {{-- Desktop View --}}
                <div class="buyle-flow-canvas">
                    {{-- SVG Background Track Layer (Matching Reference Image Arc) --}}
                    <svg class="buyle-flow-track-layer" viewBox="0 0 1140 680" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="flowTrackGradBuyer" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#1eb349" />
                                <stop offset="100%" stop-color="#a5cf37" />
                            </linearGradient>
                        </defs>

                        {{-- Hub Surrounding Decorative Rings --}}
                        <circle cx="240" cy="340" r="232" stroke="rgba(30, 179, 73, 0.16)" stroke-width="1.5" />
                        <circle cx="240" cy="340" r="255" stroke="rgba(30, 179, 73, 0.09)" stroke-width="1" stroke-dasharray="4 6" />
                        <path d="M 130 180 A 225 225 0 0 1 350 180" stroke="url(#flowTrackGradBuyer)" stroke-width="5" stroke-linecap="round" />
                        <path d="M 130 500 A 225 225 0 0 0 350 500" stroke="url(#flowTrackGradBuyer)" stroke-width="5" stroke-linecap="round" />

                        {{-- Thick Green Semicircle Ribbon Track (The "Setengah Lingkaran" Arc) --}}
                        <path d="M 395 49 A 330 330 0 0 1 570 340 A 330 330 0 0 1 395 631" stroke="#EBF8ED" stroke-width="84" stroke-linecap="round" />
                        <path d="M 395 49 A 330 330 0 0 1 570 340 A 330 330 0 0 1 395 631" stroke="rgba(30, 179, 73, 0.22)" stroke-width="86" stroke-linecap="round" />
                        <path d="M 395 49 A 330 330 0 0 1 570 340 A 330 330 0 0 1 395 631" stroke="rgba(30, 179, 73, 0.45)" stroke-width="2" stroke-linecap="round" stroke-dasharray="6 8" />

                        {{-- Smooth 4s Glowing Animated Beam --}}
                        <path class="buyle-flow-track-glow" d="M 395 49 A 330 330 0 0 1 570 340 A 330 330 0 0 1 395 631" stroke="url(#flowTrackGradBuyer)" stroke-width="5" stroke-linecap="round" stroke-dasharray="750" stroke-dashoffset="750" />
                    </svg>

                    {{-- Left Central Hub Circle --}}
                    <div class="buyle-flow-hub-wrap">
                        <div class="buyle-flow-hub-card">
                            <div class="buyle-hub-subtitle-top">PANDUAN PEMBELI</div>
                            <h3 class="buyle-hub-main-title">
                                5 Langkah
                                <span class="accent">Mudah Belanja</span>
                                di Buyle.id
                            </h3>
                            <div class="buyle-hub-accent-bar"></div>
                            <p class="buyle-hub-desc">
                                Transaksi aman, instan, otomatis 24 jam, langsung dapat diunduh tanpa menunggu verifikasi manual.
                            </p>
                        </div>
                    </div>

                    {{-- Right Steps Arc Group (5 Nodes Centered INSIDE the Ribbon with Connectors and Cards) --}}
                    <div class="buyle-flow-nodes">
                        {{-- Step 01 --}}
                        <div class="buyle-flow-step step-pos-1">
                            <div class="buyle-flow-node-num">
                                <span>01</span>
                            </div>
                            <div class="buyle-flow-pointer">
                                <div class="buyle-flow-pointer-line"></div>
                                <div class="buyle-flow-pointer-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </div>
                            <div class="buyle-flow-card">
                                <div class="buyle-flow-card-head">
                                    <div class="buyle-flow-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                    </div>
                                    <h4 class="buyle-flow-card-title">Eksplorasi Produk</h4>
                                </div>
                                <p class="buyle-flow-card-desc">Cari template, kursus, tiket seminar, e-book, & jasa profesional terbaik.</p>
                            </div>
                        </div>

                        {{-- Step 02 --}}
                        <div class="buyle-flow-step step-pos-2">
                            <div class="buyle-flow-node-num">
                                <span>02</span>
                            </div>
                            <div class="buyle-flow-pointer">
                                <div class="buyle-flow-pointer-line"></div>
                                <div class="buyle-flow-pointer-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </div>
                            <div class="buyle-flow-card">
                                <div class="buyle-flow-card-head">
                                    <div class="buyle-flow-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </div>
                                    <h4 class="buyle-flow-card-title">Pilih Paket & Lisensi</h4>
                                </div>
                                <p class="buyle-flow-card-desc">Cek preview materi, spesifikasi lisensi, dan ulasan kepuasan pembeli.</p>
                            </div>
                        </div>

                        {{-- Step 03 --}}
                        <div class="buyle-flow-step step-pos-3">
                            <div class="buyle-flow-node-num">
                                <span>03</span>
                            </div>
                            <div class="buyle-flow-pointer">
                                <div class="buyle-flow-pointer-line"></div>
                                <div class="buyle-flow-pointer-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </div>
                            <div class="buyle-flow-card">
                                <div class="buyle-flow-card-head">
                                    <div class="buyle-flow-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                        </svg>
                                    </div>
                                    <h4 class="buyle-flow-card-title">Checkout Kilat</h4>
                                </div>
                                <p class="buyle-flow-card-desc">Cukup isi email & nomor WhatsApp penerima tanpa wajib daftar akun yang ribet.</p>
                            </div>
                        </div>

                        {{-- Step 04 --}}
                        <div class="buyle-flow-step step-pos-4">
                            <div class="buyle-flow-node-num">
                                <span>04</span>
                            </div>
                            <div class="buyle-flow-pointer">
                                <div class="buyle-flow-pointer-line"></div>
                                <div class="buyle-flow-pointer-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </div>
                            <div class="buyle-flow-card">
                                <div class="buyle-flow-card-head">
                                    <div class="buyle-flow-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                                            <line x1="12" y1="18" x2="12.01" y2="18"></line>
                                        </svg>
                                    </div>
                                    <h4 class="buyle-flow-card-title">Bayar Instan Otomatis</h4>
                                </div>
                                <p class="buyle-flow-card-desc">Scan QRIS, Virtual Account semua bank, atau Kartu Kredit terkonfirmasi detik itu juga.</p>
                            </div>
                        </div>

                        {{-- Step 05 --}}
                        <div class="buyle-flow-step step-pos-5">
                            <div class="buyle-flow-node-num">
                                <span>05</span>
                            </div>
                            <div class="buyle-flow-pointer">
                                <div class="buyle-flow-pointer-line"></div>
                                <div class="buyle-flow-pointer-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </div>
                            <div class="buyle-flow-card">
                                <div class="buyle-flow-card-head">
                                    <div class="buyle-flow-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                    </div>
                                    <h4 class="buyle-flow-card-title">Akses Produk 24/7</h4>
                                </div>
                                <p class="buyle-flow-card-desc">Link unduh produk, lisensi & invoice otomatis dikirim langsung ke email & WhatsApp.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mobile Timeline View --}}
                <div class="buyle-flow-mobile-view">
                    <div class="buyle-mobile-hub">
                        <div class="buyle-hub-subtitle-top">PANDUAN PEMBELI</div>
                        <h3 class="buyle-hub-main-title" style="font-size: 1.6rem;">
                            5 Langkah <span class="accent">Mudah Belanja</span> di Buyle.id
                        </h3>
                        <div class="buyle-hub-accent-bar"></div>
                        <p class="buyle-hub-desc" style="max-width: 100%;">
                            Transaksi instan, otomatis 24 jam dengan ratusan pilihan produk digital terpercaya.
                        </p>
                    </div>

                    <div class="buyle-mobile-timeline">
                        <div class="buyle-mobile-step-item">
                            <div class="buyle-mobile-node-num"><span>01</span></div>
                            <div class="buyle-mobile-card-body">
                                <div class="buyle-mobile-card-top">
                                    <div class="buyle-mobile-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                    </div>
                                    <h4 class="buyle-mobile-card-title">Eksplorasi Produk</h4>
                                </div>
                                <p class="buyle-mobile-card-desc">Cari template, kursus, tiket seminar, e-book, & jasa profesional terbaik.</p>
                            </div>
                        </div>

                        <div class="buyle-mobile-step-item">
                            <div class="buyle-mobile-node-num"><span>02</span></div>
                            <div class="buyle-mobile-card-body">
                                <div class="buyle-mobile-card-top">
                                    <div class="buyle-mobile-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    </div>
                                    <h4 class="buyle-mobile-card-title">Pilih Paket & Lisensi</h4>
                                </div>
                                <p class="buyle-mobile-card-desc">Cek preview materi, spesifikasi lisensi, dan ulasan kepuasan pembeli.</p>
                            </div>
                        </div>

                        <div class="buyle-mobile-step-item">
                            <div class="buyle-mobile-node-num"><span>03</span></div>
                            <div class="buyle-mobile-card-body">
                                <div class="buyle-mobile-card-top">
                                    <div class="buyle-mobile-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                    </div>
                                    <h4 class="buyle-mobile-card-title">Checkout Kilat</h4>
                                </div>
                                <p class="buyle-mobile-card-desc">Cukup isi email & nomor WhatsApp penerima tanpa wajib daftar akun yang ribet.</p>
                            </div>
                        </div>

                        <div class="buyle-mobile-step-item">
                            <div class="buyle-mobile-node-num"><span>04</span></div>
                            <div class="buyle-mobile-card-body">
                                <div class="buyle-mobile-card-top">
                                    <div class="buyle-mobile-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                                    </div>
                                    <h4 class="buyle-mobile-card-title">Bayar Instan Otomatis</h4>
                                </div>
                                <p class="buyle-mobile-card-desc">Scan QRIS, Virtual Account semua bank, atau Kartu Kredit terkonfirmasi detik itu juga.</p>
                            </div>
                        </div>

                        <div class="buyle-mobile-step-item">
                            <div class="buyle-mobile-node-num"><span>05</span></div>
                            <div class="buyle-mobile-card-body">
                                <div class="buyle-mobile-card-top">
                                    <div class="buyle-mobile-card-icon">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    </div>
                                    <h4 class="buyle-mobile-card-title">Akses Produk 24/7</h4>
                                </div>
                                <p class="buyle-mobile-card-desc">Link unduh produk, lisensi & invoice otomatis dikirim langsung ke email & WhatsApp.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- ════ 3. VISI & MISI ════ --}}
    <section style="padding: 5rem 1.5rem; background: var(--cv-surface); border-top: 1px solid var(--cv-border);">
        <div class="container">
            <div style="text-align:center; max-width:800px; margin:0 auto 3rem;">
                <div class="cv-adv-section-label" style="justify-content:center;">PROFIL</div>
                <h2 class="cv-adv-section-title" style="text-align:center;">Visi &amp; Misi Kami</h2>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px,1fr)); gap:1.5rem;">
                <div class="cv-card cv-card-gray" style="min-height:auto;" data-aos="fade-up">
                    <div class="cv-card-content">
                        <div class="cv-card-label" style="color:var(--cv-accent); font-weight:700;">Visi Platform</div>
                        <h3 style="font-size:1.5rem; font-weight:600; color:var(--cv-text); margin-bottom:1rem;">Menjadi
                            Pelopor</h3>
                        <p style="font-size:0.9375rem; color:#475569; line-height:1.7; margin:0;">
                            {{ $settings['visi'] ?? 'Menjadi digital creator marketplace terdepan di Indonesia yang menghubungkan kreator berbakat dengan pembeli yang mencari karya dan layanan digital berkualitas.' }}
                        </p>
                    </div>
                </div>
                <div class="cv-card cv-card-gray" style="min-height:auto;" data-aos="fade-up" data-aos-delay="100">
                    <div class="cv-card-content">
                        <div class="cv-card-label" style="color:var(--cv-accent); font-weight:700;">Misi Platform</div>
                        <h3 style="font-size:1.5rem; font-weight:600; color:var(--cv-text); margin-bottom:1rem;">Ekosistem
                            Digital</h3>
                        <p style="font-size:0.9375rem; color:#475569; line-height:1.7; margin:0;">
                            {{ $settings['misi'] ?? 'Membangun ekosistem marketplace yang adil, aman, dan menguntungkan bagi kreator dan pembeli; mendorong pertumbuhan ekonomi digital Indonesia.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════ INJECT STYLES AND SECTIONS FROM HOME ════ --}}
    <style>
        /* ── KEUNGGULAN ───────────────────────────── */
        .cv-adv-premium {
            background: #ffffff;
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }

        .cv-adv-premium::before {
            content: '';
            position: absolute;
            top: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(30, 179, 73, 0.04) 0%, transparent 70%);
            pointer-events: none;
        }

        .cv-adv-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Header row: label + title left, CTA right */
        .cv-adv-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 2rem;
            margin-bottom: 3.5rem;
            flex-wrap: wrap;
        }

        .cv-adv-section-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #64748B;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .cv-adv-section-label::before {
            content: '';
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #1eb349;
        }

        .cv-adv-section-title {
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 500;
            color: #0F172A;
            line-height: 1.15;
            letter-spacing: -0.025em;
        }

        /* Premium 7-card grid — matches about section */
        .cv-adv-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }

        /* Special first card: full-height accent (like about card-2) */
        .cv-adv-card-v2 {
            background: #F1F5F9;
            border-radius: 22px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 240px;
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.3s;
        }

        .cv-adv-card-v2:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(30, 179, 73, 0.1);
        }

        .cv-adv-card-v2.accent {
            background: #1eb349;
        }

        .cv-adv-card-v2.accent-dark {
            background: #0F172A;
        }

        .cv-adv-card-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            flex-shrink: 0;
        }

        .cv-adv-card-icon-wrap.blue-bg {
            background: #dcfce7;
            color: #1eb349;
        }

        .cv-adv-card-icon-wrap.white-bg {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .cv-adv-card-icon-wrap.dark-bg {
            background: rgba(255, 255, 255, 0.06);
            color: #a5cf37;
        }

        .cv-adv-card-num {
            font-size: 2.75rem;
            font-weight: 400;
            line-height: 1;
            letter-spacing: -0.04em;
            color: #0F172A;
            margin-bottom: 0.5rem;
        }

        .cv-adv-card-num.white {
            color: #fff;
        }

        .cv-adv-card-num.blue {
            color: #a5cf37;
        }

        .cv-adv-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #0F172A;
            margin-bottom: 0.5rem;
        }

        .cv-adv-card-title.white {
            color: #fff;
        }

        .cv-adv-card-title.light {
            color: rgba(255, 255, 255, 0.9);
        }

        .cv-adv-card-desc {
            font-size: 0.8125rem;
            line-height: 1.65;
            color: #64748B;
            margin-top: auto;
        }

        .cv-adv-card-desc.white {
            color: rgba(255, 255, 255, 0.75);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .cv-adv-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .cv-adv-premium {
                padding: 3.5rem 0;
            }

            .cv-adv-cards {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .cv-adv-card-v2 {
                min-height: 180px;
            }
        }

        /* ── APLIKASI ─────────────────────────────── */
        .cv-apps-premium {
            background: #F8FAFC;
            padding: 5rem 0;
        }

        .cv-apps-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .cv-apps-header {
            max-width: 600px;
            margin-bottom: 3rem;
        }

        /* Horizontal scroll row of app cards */
        .cv-apps-grid-v2 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }

        .cv-app-card-v2 {
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 1.75rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .cv-app-card-v2:hover {
            border-color: #1eb349;
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(30, 179, 73, 0.1);
        }

        .cv-app-icon-circle {
            width: 50px;
            height: 50px;
            background: #F0F9FF;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1eb349;
            flex-shrink: 0;
            transition: all 0.3s;
        }

        .cv-app-card-v2:hover .cv-app-icon-circle {
            background: #1eb349;
            color: #fff;
        }

        .cv-app-card-title-v2 {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #0F172A;
            margin: 0;
        }

        .cv-app-card-desc-v2 {
            font-size: 0.8125rem;
            color: #64748B;
            line-height: 1.65;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .cv-apps-grid-v2 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .cv-apps-premium {
                padding: 3.5rem 0;
            }

            .cv-apps-grid-v2 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
    <section class="cv-adv-premium" id="keunggulan">
        <div class="cv-adv-inner">
            {{-- Section Header --}}
            <div class="cv-adv-header">
                <div>
                    <div class="cv-adv-section-label">KEUNGGULAN</div>
                    <h2 class="cv-adv-section-title">Mengapa Pilih<br>buyle.id?</h2>
                </div>
                <p style="max-width:320px;font-size:0.875rem;color:#64748B;line-height:1.65;text-align:right;">
                    Platform yang dirancang khusus untuk ekosistem digital creator Indonesia, terpercaya oleh ribuan kreator
                    digital dan pembeli.
                </p>
            </div>

            {{-- Premium Cards Grid --}}
            <div class="cv-adv-cards">

                {{-- Card 1: E-Ticketing & Event Pass — Accent Green --}}
                <div class="cv-adv-card-v2 accent" data-aos="fade-up" data-aos-delay="0">
                    <div class="cv-adv-card-icon-wrap white-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path
                                d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" />
                            <path d="M13 5v2m0 4v2m0 4v2" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-num white">100%</div>
                    <div class="cv-adv-card-title white">E-Ticketing & Event Pass</div>
                    <div class="cv-adv-card-desc white">Sistem tiket digital konser, workshop, webinar, hingga tempat
                        wisata. Dilengkapi QR Code scan cepat anti-duplikasi & dashboard check-in gate staff.</div>
                </div>

                {{-- Card 2: Web & Bio Link Builder — Gray --}}
                <div class="cv-adv-card-v2" data-aos="fade-up" data-aos-delay="80">
                    <div class="cv-adv-card-icon-wrap blue-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <path d="M3 9h18M9 21V9" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-num">⚡</div>
                    <div class="cv-adv-card-title">Web & Bio Link Builder</div>
                    <div class="cv-adv-card-desc">Bikin landing page dan bio link toko online kustom instan tanpa koding.
                        Dukungan kustom domain, tema estetik, formulir order, dan analitik pengunjung.</div>
                </div>

                {{-- Card 3: Whitelabel & Jual Produk Orang (Affiliate / Reseller) — Gray --}}
                <div class="cv-adv-card-v2" data-aos="fade-up" data-aos-delay="160">
                    <div class="cv-adv-card-icon-wrap blue-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="m17 2 4 4-4 4" />
                            <path d="M3 11v-1a4 4 0 0 1 4-4h14" />
                            <path d="m7 22-4-4 4-4" />
                            <path d="M21 13v1a4 4 0 0 1-4 4H3" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-num">Margin Bebas</div>
                    <div class="cv-adv-card-title">Whitelabel & Jual Produk Mitra</div>
                    <div class="cv-adv-card-desc">Mulai bisnis tanpa modal produk sendiri. Jual produk digital kreator lain
                        dengan harga Anda sendiri (whitelabel) atau dapatkan komisi afiliasi otomatis.</div>
                </div>

                {{-- Card 4: Transaksi Aman Escrow — Dark --}}
                <div class="cv-adv-card-v2 accent-dark" data-aos="fade-up" data-aos-delay="240">
                    <div class="cv-adv-card-icon-wrap dark-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-num blue">100%</div>
                    <div class="cv-adv-card-title light">Transaksi Terlindungi Escrow</div>
                    <div class="cv-adv-card-desc white">Dana pembeli tersimpan aman di rekening penampung (escrow) sampai
                        produk diterima. Kreator dapat mencairkan saldo kapan saja tanpa risiko.</div>
                </div>

                {{-- Card 5: Marketplace Produk Digital --}}
                <div class="cv-adv-card-v2" data-aos="fade-up" data-aos-delay="0">
                    <div class="cv-adv-card-icon-wrap blue-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-title" style="margin-top:auto;">Produk Digital & Akses Instan</div>
                    <div class="cv-adv-card-desc">Template Canva & Notion, source code aplikasi, desain grafis, e-book,
                        hingga course. File langsung terkirim dan bisa diunduh detik itu juga.</div>
                </div>

                {{-- Card 6: Jasa Kreator & Sesi 1-on-1 --}}
                <div class="cv-adv-card-v2" data-aos="fade-up" data-aos-delay="80">
                    <div class="cv-adv-card-icon-wrap blue-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-title" style="margin-top:auto;">Jasa Kreator & Sesi 1-on-1</div>
                    <div class="cv-adv-card-desc">Booking jasa freelance profesional dan sesi konsultasi privat dengan
                        kreator favorit lewat kalender jadwal tersinkronisasi dan brief terorganisir.</div>
                </div>

                {{-- Card 7: Payment Gateway & Payout — spans 2 columns --}}
                <div class="cv-adv-card-v2" data-aos="fade-up" data-aos-delay="160"
                    style="grid-column: span 2; flex-direction: row; gap: 2rem; align-items: center;">
                    <div class="cv-adv-card-icon-wrap blue-bg" style="flex-shrink:0; width:60px; height:60px;">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <div class="cv-adv-card-title" style="font-size:1.125rem; margin-bottom:0.5rem;">Pembayaran
                            Terlengkap & Payout Cepat</div>
                        <div class="cv-adv-card-desc">Mendukung pembayaran via QRIS semua bank/e-wallet (GoPay, OVO, DANA,
                            ShopeePay), Virtual Account, dan Kartu Kredit. Pencairan saldo mudah dan transparan.</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @if($testimonials->count())
        <style>
            /* ── GALERI ─────────────────────────────── */
            .cv-gallery-premium {
                background: #ffffff;
                padding: 5rem 0;
            }

            .cv-gallery-inner {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 1.5rem;
            }

            .cv-gallery-header {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                gap: 2rem;
                margin-bottom: 3.5rem;
                flex-wrap: wrap;
            }

            .cv-gallery-grid-v2 {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }

            .cv-gallery-card-v2 {
                position: relative;
                border-radius: 20px;
                overflow: hidden;
                aspect-ratio: 4/3;
                display: block;
                background: #F1F5F9;
            }

            .cv-gallery-img-v2 {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
            }

            .cv-gallery-card-v2:hover .cv-gallery-img-v2 {
                transform: scale(1.08);
            }

            .cv-gallery-overlay-v2 {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0) 60%);
                display: flex;
                align-items: flex-end;
                padding: 1.5rem;
                transition: background 0.3s;
            }

            .cv-gallery-card-v2:hover .cv-gallery-overlay-v2 {
                background: linear-gradient(to top, rgba(30, 179, 73, 0.9) 0%, rgba(15, 23, 42, 0) 70%);
            }

            .cv-gallery-meta-v2 {
                color: #fff;
                transform: translateY(10px);
                transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            }

            .cv-gallery-card-v2:hover .cv-gallery-meta-v2 {
                transform: translateY(0);
            }

            .cv-gallery-title-v2 {
                font-size: 1.125rem;
                font-weight: 600;
                margin-bottom: 0.25rem;
            }

            .cv-gallery-client-v2 {
                font-size: 0.8125rem;
                color: rgba(255, 255, 255, 0.75);
            }

            /* ── TESTIMONI ──────────────────────────── */
            .cv-testi-premium {
                background: #F8FAFC;
                padding: 5rem 0;
                position: relative;
                overflow: hidden;
            }

            .cv-testi-premium::before {
                content: '';
                position: absolute;
                bottom: -200px;
                left: -200px;
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(30, 179, 73, 0.04) 0%, transparent 70%);
                pointer-events: none;
            }

            .cv-testi-inner {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 1.5rem;
            }

            .cv-testi-grid-v2 {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }

            .cv-testi-card-v2 {
                background: #ffffff;
                border: 1.5px solid #E2E8F0;
                border-radius: 20px;
                padding: 2.25rem;
                position: relative;
                transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            }

            .cv-testi-card-v2:hover {
                border-color: #1eb349;
                transform: translateY(-6px);
                box-shadow: 0 16px 40px rgba(30, 179, 73, 0.1);
            }

            .cv-testi-quote-icon {
                position: absolute;
                top: 1.5rem;
                right: 1.5rem;
                color: #F1F5F9;
                width: 48px;
                height: 48px;
                transition: color 0.3s;
            }

            .cv-testi-card-v2:hover .cv-testi-quote-icon {
                color: #dcfce7;
            }

            .cv-testi-stars-v2 {
                display: flex;
                gap: 0.25rem;
                color: #F59E0B;
                margin-bottom: 1.25rem;
            }

            .cv-testi-text-v2 {
                font-size: 0.9375rem;
                line-height: 1.7;
                color: #475569;
                margin-bottom: 2rem;
                position: relative;
                z-index: 1;
            }

            .cv-testi-author-row {
                display: flex;
                align-items: center;
                gap: 1rem;
                border-top: 1px solid #F1F5F9;
                padding-top: 1.25rem;
            }

            .cv-testi-avatar-v2 {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: #F1F5F9;
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.125rem;
                font-weight: 600;
                object-fit: cover;
            }

            .cv-testi-name-v2 {
                font-size: 0.9375rem;
                font-weight: 700;
                color: #0F172A;
                margin-bottom: 0.15rem;
            }

            .cv-testi-pos-v2 {
                font-size: 0.75rem;
                color: #64748B;
            }

            /* Responsive */
            @media (max-width: 1024px) {
                .cv-gallery-grid-v2 {
                    grid-template-columns: repeat(2, 1fr);
                }

                .cv-testi-grid-v2 {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 640px) {

                .cv-gallery-premium,
                .cv-testi-premium {
                    padding: 3.5rem 0;
                }

                .cv-gallery-grid-v2,
                .cv-testi-grid-v2 {
                    grid-template-columns: 1fr;
                }
            }
        </style>
        <section class="cv-testi-premium" id="testimoni">
            <div class="cv-testi-inner">
                <div style="text-align:center; max-width:600px; margin:0 auto 3.5rem;">
                    <div class="cv-adv-section-label" style="justify-content:center;">TESTIMONI KLIEN</div>
                    <h2 class="cv-adv-section-title" style="margin-top:0.75rem;">Kata Mereka yang<br>Sudah Menggunakan</h2>
                </div>

                <div class="cv-testi-grid-v2">
                    @foreach($testimonials->take(3) as $i => $testi)
                        <div class="cv-testi-card-v2" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                            <svg class="cv-testi-quote-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                            <div class="cv-testi-stars-v2">
                                @for($s = 0; $s < ($testi->rating ?? 5); $s++)
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="cv-testi-text-v2">"{{ $testi->content }}"</p>
                            <div class="cv-testi-author-row">
                                <img src="{{ $testi->photo_url }}" alt="{{ $testi->name }}" class="cv-testi-avatar-v2">
                                <div>
                                    <div class="cv-testi-name-v2">{{ $testi->name }}</div>
                                    <div class="cv-testi-pos-v2">{{ $testi->position }} — {{ $testi->company }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- MARQUEE KLIEN --}}
    @if($clients->count())
        <section class="cv-clients-bar">
            <div class="container" style="margin-bottom:1.5rem; text-align:center;">
                <div class="cv-label">Klien Aktif</div>
                <h2 class="cv-heading" style="font-size:clamp(1.5rem,2.5vw,2rem);">Dipercaya oleh Perusahaan Terkemuka</h2>
            </div>
            <div class="cv-marquee-container">
                <div class="cv-marquee-track">
                    @foreach([1, 2] as $loopGroup)
                        @foreach($clients as $c)
                            @if($c->logo)
                                <div class="cv-client-logo-card">
                                    <img src="{{ asset('storage/' . $c->logo) }}" alt="{{ $c->name }}" class="cv-client-logo-img"
                                        title="{{ $c->name }}">
                                </div>
                            @else
                                <div class="cv-client-chip-v2">{{ $c->name }}</div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Interactive Script for Switch & Scroll Animation on How It Works --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ── Switch Tabs for "Cara Jadi Creator vs Pembeli" ──
            const switchBox = document.getElementById('flowSwitchBox');
            const tabCreator = document.getElementById('tabFlowCreator');
            const tabBuyer = document.getElementById('tabFlowBuyer');
            const panelCreator = document.getElementById('flowPanelCreator');
            const panelBuyer = document.getElementById('flowPanelBuyer');

            function switchFlow(flow) {
                if (!switchBox || !panelCreator || !panelBuyer) return;

                if (flow === 'creator') {
                    tabCreator.classList.add('active');
                    tabBuyer.classList.remove('active');
                    switchBox.classList.remove('show-buyer');

                    panelBuyer.classList.remove('active', 'animating-in');
                    panelCreator.classList.add('active');
                    void panelCreator.offsetWidth;
                    panelCreator.classList.add('animating-in');
                } else {
                    tabBuyer.classList.add('active');
                    tabCreator.classList.remove('active', 'animating-in');
                    switchBox.classList.add('show-buyer');

                    panelBuyer.classList.add('active');
                    void panelBuyer.offsetWidth;
                    panelBuyer.classList.add('animating-in');
                }
            }

            if (tabCreator && tabBuyer) {
                tabCreator.addEventListener('click', function () {
                    switchFlow('creator');
                });
                tabBuyer.addEventListener('click', function () {
                    switchFlow('buyer');
                });
            }

            // ── Interactive Scroll Animation Observer ──
            const flowSection = document.getElementById('buyle-how-it-works');
            if (flowSection) {
                if ('IntersectionObserver' in window) {
                    const flowObserver = new IntersectionObserver(function (entries) {
                        entries.forEach(function (entry) {
                            if (entry.isIntersecting) {
                                flowSection.classList.add('in-view');
                                flowObserver.unobserve(flowSection);
                            }
                        });
                    }, {
                        threshold: 0.12,
                        rootMargin: '0px 0px -50px 0px'
                    });
                    flowObserver.observe(flowSection);
                } else {
                    flowSection.classList.add('in-view');
                }
            }
        });
    </script>

@endsection