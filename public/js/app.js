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

// ── Module Drag-to-Reorder ─────────────────────────────────────────
// Usage: initModuleSorting(programId, csrfToken)
// Requires SortableJS (loaded via CDN in layouts/app.blade.php)
function initModuleSorting(programId, csrfToken) {
    const list = document.getElementById('modules-sortable');
    if (!list || typeof Sortable === 'undefined') return;

    Sortable.create(list, {
        handle: '.ain-drag-handle',
        animation: 150,
        ghostClass: 'ain-drag-ghost',
        onEnd: function () {
            const ids = Array.from(list.children).map(function (el) {
                return parseInt(el.dataset.id, 10);
            });

            fetch('/lawyer/programs/' + programId + '/modules/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ ids: ids }),
            })
            .then(function (res) {
                if (res.ok) {
                    initSimpleToast('Module order saved', 'success');
                }
            });
        },
    });
}

// ── Simple Toast ───────────────────────────────────────────────────
// Creates a fixed dismissible Bootstrap alert and auto-removes after 3s.
function initSimpleToast(message, type) {
    type = type || 'success';

    var wrapper = document.createElement('div');
    wrapper.style.cssText = 'position:fixed;top:1rem;right:1rem;z-index:9999;min-width:260px';

    var alert = document.createElement('div');
    alert.className = 'alert alert-' + type + ' alert-dismissible fade show shadow';
    alert.setAttribute('role', 'alert');
    alert.innerHTML =
        message +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

    wrapper.appendChild(alert);
    document.body.appendChild(wrapper);

    setTimeout(function () {
        wrapper.remove();
    }, 3000);
}
