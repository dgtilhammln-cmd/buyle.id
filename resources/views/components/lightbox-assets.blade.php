<link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" media="print" onload="this.media='all'" />
<noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" /></noscript>
<style>
/* ═══════════════════════════════════════
   PREMIUM LIGHTBOX (GLIGHTBOX OVERRIDE)
═══════════════════════════════════════ */
.goverlay {
    background: rgba(15, 23, 42, 0.95) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
.glightbox-clean .gslide-media {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 24px 50px rgba(0,0,0,0.4);
}
.glightbox-clean .gslide-description {
    background: #ffffff !important;
    border-radius: 16px !important;
    padding: 1.5rem !important;
    margin-top: 1.25rem !important;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
.glightbox-clean .gslide-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 1.125rem !important;
    font-weight: 700 !important;
    color: #0F172A !important;
    margin-bottom: 0.35rem !important;
}
.glightbox-clean .gslide-desc {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    color: #64748B !important;
    margin: 0 !important;
}
.gnext, .gprev, .gclose {
    background: rgba(255, 255, 255, 0.1) !important;
    border-radius: 50% !important;
    color: #ffffff !important;
    transition: all 0.3s ease !important;
    width: 44px !important;
    height: 44px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.gnext:hover, .gprev:hover, .gclose:hover {
    background: #0EA5E9 !important;
    transform: scale(1.1);
}
.gnext svg, .gprev svg, .gclose svg {
    width: 24px !important;
    height: 24px !important;
}
.gclose {
    top: 20px !important;
    right: 20px !important;
    background: rgba(239, 68, 68, 0.8) !important;
}
.gclose:hover {
    background: #EF4444 !important;
}
@media (min-width: 769px) {
    .glightbox-clean .gslide-description {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        width: auto !important;
        min-width: 300px;
        max-width: 80%;
    }
}
</style>

<script defer src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        zoomable: true,
        closeButton: true,
        openEffect: 'zoom',
        closeEffect: 'zoom',
        slideEffect: 'slide',
        cssEfects: {
            fade: { in: 'fadeIn', out: 'fadeOut' },
            zoom: { in: 'zoomIn', out: 'zoomOut' }
        }
    });
});
</script>
