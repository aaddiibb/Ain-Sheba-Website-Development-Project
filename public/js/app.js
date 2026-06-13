/* =========================================================
   Ain Sheba — App JavaScript (vanilla JS, no jQuery)
   Page-specific scripts go in @push('scripts') blocks.
   CSRF token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    // ── Counter Animation ──────────────────────────────────────────
    // Animates .ain-counter[data-target] from 0 → target over 1.5s
    // with ease-out. Triggers once when 40% of element is in viewport.
    const counters = document.querySelectorAll('.ain-counter[data-target]');

    if (counters.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;

                const el     = entry.target;
                const target = parseInt(el.dataset.target, 10);
                const suffix = el.dataset.suffix || '';
                const duration = 1500;
                const start    = performance.now();

                function tick(now) {
                    const elapsed  = now - start;
                    const progress = Math.min(elapsed / duration, 1);
                    // ease-out cubic
                    const eased    = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target) + suffix;

                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    } else {
                        el.textContent = target + suffix;
                    }
                }

                requestAnimationFrame(tick);
                obs.unobserve(el);
            });
        }, { threshold: 0.4 });

        counters.forEach(function (el) {
            observer.observe(el);
        });
    }

});

// ── Image Preview ──────────────────────────────────────────────────
// Usage: initImagePreview('fileInputId', 'previewImgId')
function initImagePreview(inputId, previewId) {
    const input   = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (!input || !preview) return;

    input.addEventListener('change', function () {
        if (!this.files || !this.files[0]) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    });
}
