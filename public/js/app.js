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

// ── Booking Date → Time Slot Generator ────────────────────────────
// Used on citizen/consultations/book.blade.php.
// Expects window.lawyerAvailability (grouped by day_of_week) and
// window.bookedSlots ([{booked_date, time_slot}]) to be set by the view.
document.getElementById('booking-date')?.addEventListener('change', function () {
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    // Use UTC date parts to avoid timezone shift on date-only strings
    var parts = this.value.split('-');
    var d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
    var selectedDay = days[d.getDay()];
    var slot = lawyerAvailability[selectedDay];
    var timeSelect = document.getElementById('time-slot-select');

    timeSelect.innerHTML = '<option value="">Select a time slot</option>';

    if (!slot || slot.length === 0) {
        timeSelect.innerHTML = '<option value="">No availability on this day</option>';
        return;
    }

    var selectedDate = this.value;

    slot.forEach(function (s) {
        var startH = parseInt(s.start_time);
        var endH   = parseInt(s.end_time);

        for (var h = startH; h < endH; h++) {
            var label = h + ':00 - ' + (h + 1) + ':00';
            var taken = bookedSlots.some(function (b) {
                return b.booked_date === selectedDate && b.time_slot === label;
            });

            var opt = document.createElement('option');
            opt.value       = label;
            opt.textContent = taken ? label + ' (Taken)' : label;
            if (taken) { opt.disabled = true; }
            timeSelect.appendChild(opt);
        }
    });
});

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

// ── Module Completion ─────────────────────────────────────────────
function markModuleComplete(moduleId, csrfToken) {
    const btn = document.getElementById('btn-mark-complete');
    if (!btn) return;

    btn.addEventListener('click', function () {
        btn.disabled = true;
        btn.textContent = 'Marking...';

        fetch('/citizen/modules/' + moduleId + '/complete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(function (r) {
            return r.json();
        })
        .then(function (data) {
            btn.style.display = 'none';

            document.getElementById('completion-badge')?.style.setProperty('display', 'inline-flex');

            const currentItem = document.querySelector('.ain-module-item.active .ain-module-icon');
            if (currentItem) {
                currentItem.className = 'bi bi-check-circle-fill text-success ain-module-icon';
            }

            const counter = document.getElementById('module-counter');
            if (counter && data.completed_count !== undefined) {
                counter.textContent = data.completed_count + ' of ' + data.total_count + ' completed';
            }

            if (data.program_completed) {
                initSimpleToast('🎉 You completed this program! Your Legal Literacy Certificate has been issued.', 'success');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.textContent = 'Mark as Complete';
        });
    });
}

const moduleCompleteButton = document.getElementById('btn-mark-complete');
if (moduleCompleteButton) {
    markModuleComplete(
        moduleCompleteButton.dataset.moduleId,
        document.querySelector('meta[name="csrf-token"]')?.content || ''
    );
}
