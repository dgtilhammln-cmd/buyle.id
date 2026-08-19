// ================================================
// NAVBAR SCROLL EFFECT
// ================================================
const navbar = document.getElementById('navbar');
if (navbar) {
    const handleScroll = () => {
        if (window.scrollY > 60) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    };
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
}

// ================================================
// TRACKING HELPERS
// ================================================
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

async function trackEvent(type, url = window.location.href) {
    try {
        await fetch(`/track/${type}`, {
            method: 'POST',
            keepalive: true,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ url }),
        });
    } catch (e) {
        // Silent fail - tracking should not block user action
    }
}

// Track WA clicks
document.querySelectorAll('[data-track="wa"]').forEach(el => {
    el.addEventListener('click', () => trackEvent('wa_click'));
});

// Track phone clicks
document.querySelectorAll('[data-track="phone"]').forEach(el => {
    el.addEventListener('click', () => trackEvent('phone_click'));
});

// Track email clicks
document.querySelectorAll('[data-track="email"]').forEach(el => {
    el.addEventListener('click', () => trackEvent('email_click'));
});

// ================================================
// COUNTER ANIMATION (Stats section)
// ================================================
function animateCounter(el) {
    const target = parseInt(el.dataset.target || el.textContent);
    if (isNaN(target)) return;
    const duration = 2000;
    const step     = Math.ceil(target / (duration / 16));
    let current    = 0;
    const suffix   = el.dataset.suffix || '';
    const timer    = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current + suffix;
        if (current >= target) clearInterval(timer);
    }, 16);
}

// Trigger counters on scroll using IntersectionObserver
const counters = document.querySelectorAll('[data-counter]');
if (counters.length) {
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                animateCounter(e.target);
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.5 });
    counters.forEach(c => io.observe(c));
}

// ================================================
// GALLERY FILTER
// ================================================
const filterBtns = document.querySelectorAll('[data-filter]');
const galleryItems = document.querySelectorAll('[data-category]');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const filter = btn.dataset.filter;

        filterBtns.forEach(b => {
            b.classList.remove('active-filter');
            b.style.background = '';
            b.style.color = '';
        });
        btn.classList.add('active-filter');
        btn.style.background = '#F5A623';
        btn.style.color = '#000';

        galleryItems.forEach(item => {
            if (filter === 'all' || item.dataset.category === filter) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// ================================================
// MOBILE MENU (fallback if no Alpine.js)
// ================================================
const mobileToggle = document.getElementById('mobile-menu-toggle');
const mobileMenu   = document.getElementById('mobile-menu');
if (mobileToggle && mobileMenu) {
    mobileToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
}

// ================================================
// IMAGE PREVIEW ON FILE INPUT
// ================================================
document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
    input.addEventListener('change', function () {
        const previewId = this.dataset.preview;
        const preview   = document.getElementById(previewId);
        if (!preview) return;
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
});
