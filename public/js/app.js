/* =========================================================
   Ain Sheba — App JavaScript (vanilla JS, no jQuery)
   Page-specific scripts go in @push('scripts') blocks.
   CSRF token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    // ── Auto-dismiss flash alerts ─────────────────────────────────────
    document.querySelectorAll('.ain-flash').forEach(function (el) {
        setTimeout(function () {
            var alert = bootstrap.Alert.getOrCreateInstance(el);
            if (alert) alert.close();
        }, 4000);
    });

    // ── Nav Search (debounced, 300ms) ────────────────────────────────
    initNavSearch();



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

// ── Assessment Builder ─────────────────────────────────────────────
// Used on lawyer/assessments/create and edit pages.
// On edit, set `var existingAssessment = @json($assessment)` before calling.
function initAssessmentBuilder() {
    var questionsList  = document.getElementById('questions-list');
    var addQuestionBtn = document.getElementById('add-question-btn');
    var emptyHint      = document.getElementById('questions-empty-hint');
    if (!questionsList || !addQuestionBtn) return;

    var qCount = 0;

    function esc(s) {
        return String(s || '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function addOption(block, optText, isCorrect) {
        if (emptyHint) emptyHint.style.display = 'none';
        var list = block.querySelector('.ain-options-list');
        var qIdx = block.dataset.qIdx;
        var oIdx = list.children.length;

        var row = document.createElement('div');
        row.className = 'ain-option-row d-flex align-items-center gap-2 mb-2';
        row.innerHTML =
            '<input type="text" name="questions[' + qIdx + '][options][' + oIdx + '][option_text]"' +
            ' class="form-control form-control-sm" placeholder="Option text"' +
            ' value="' + esc(optText) + '" required>' +
            '<div class="form-check mb-0 flex-shrink-0">' +
            '<input class="form-check-input" type="checkbox"' +
            ' name="questions[' + qIdx + '][options][' + oIdx + '][is_correct]"' +
            ' value="1" id="cor-' + qIdx + '-' + oIdx + '"' +
            (isCorrect ? ' checked' : '') + '>' +
            '<label class="form-check-label small" for="cor-' + qIdx + '-' + oIdx + '">Correct</label>' +
            '</div>' +
            '<button type="button" class="btn btn-sm btn-link text-danger p-0 flex-shrink-0">Remove</button>';

        list.appendChild(row);
        row.querySelector('button').addEventListener('click', function () { row.remove(); });
    }

    function addQuestion(text, type, points) {
        if (emptyHint) emptyHint.style.display = 'none';
        var qIdx = qCount++;

        var block = document.createElement('div');
        block.className = 'card mb-3';
        block.dataset.qIdx = qIdx;
        block.innerHTML =
            '<div class="card-header d-flex justify-content-between align-items-center py-2">' +
            '<span class="fw-semibold small">Question ' + (qIdx + 1) + '</span>' +
            '<button type="button" class="btn btn-sm btn-link text-danger p-0 remove-q">Remove</button>' +
            '</div>' +
            '<div class="card-body">' +
            '<div class="mb-3">' +
            '<label class="form-label fw-semibold small">Question Text <span class="text-danger">*</span></label>' +
            '<textarea name="questions[' + qIdx + '][question]" class="form-control" rows="2" required>' + esc(text) + '</textarea>' +
            '</div>' +
            '<div class="row g-2 mb-3">' +
            '<div class="col-md-5">' +
            '<label class="form-label fw-semibold small">Type</label>' +
            '<select name="questions[' + qIdx + '][type]" class="form-select form-select-sm">' +
            '<option value="single"' + (!type || type === 'single' ? ' selected' : '') + '>Single Choice</option>' +
            '<option value="multiple"' + (type === 'multiple' ? ' selected' : '') + '>Multiple Choice</option>' +
            '<option value="true_false"' + (type === 'true_false' ? ' selected' : '') + '>True or False</option>' +
            '</select></div>' +
            '<div class="col-md-3">' +
            '<label class="form-label fw-semibold small">Points</label>' +
            '<input type="number" name="questions[' + qIdx + '][points]" class="form-control form-control-sm" min="1" value="' + (points || 1) + '" required>' +
            '</div></div>' +
            '<p class="small fw-semibold mb-2">Options <span class="fw-normal text-muted">(tick "Correct" for right answer(s))</span></p>' +
            '<div class="ain-options-list mb-2"></div>' +
            '<button type="button" class="btn btn-sm btn-outline-secondary add-opt"><i class="bi bi-plus-lg me-1"></i>Add Option</button>' +
            '</div>';

        questionsList.appendChild(block);
        block.querySelector('.remove-q').addEventListener('click', function () { block.remove(); });
        block.querySelector('.add-opt').addEventListener('click', function () { addOption(block); });

        return block;
    }

    addQuestionBtn.addEventListener('click', function () {
        var block = addQuestion();
        addOption(block);
        addOption(block);
    });

    if (typeof existingAssessment !== 'undefined' && existingAssessment && existingAssessment.questions) {
        existingAssessment.questions.forEach(function (q) {
            var block = addQuestion(q.question, q.type, q.points);
            (q.options || []).forEach(function (opt) { addOption(block, opt.option_text, opt.is_correct); });
        });
    }
}

// ── Assessment Countdown Timer ─────────────────────────────────────
// Usage: initAssessmentTimer(totalSeconds) — auto-submits form#assessment-form at 0.
function initAssessmentTimer(totalSeconds) {
    var remaining = totalSeconds;
    var timerEl   = document.getElementById('assessment-timer');

    function pad(n) { return String(n).padStart(2, '0'); }

    function tick() {
        if (!timerEl) return;
        timerEl.textContent = pad(Math.floor(remaining / 60)) + ':' + pad(remaining % 60);
        if (remaining <= 60) timerEl.classList.add('text-danger', 'fw-bold');
    }

    tick();

    var iv = setInterval(function () {
        remaining--;
        tick();
        if (remaining <= 0) {
            clearInterval(iv);
            var form = document.getElementById('assessment-form');
            if (form) form.submit();
        }
    }, 1000);
}

const moduleCompleteButton = document.getElementById('btn-mark-complete');
if (moduleCompleteButton) {
    markModuleComplete(
        moduleCompleteButton.dataset.moduleId,
        document.querySelector('meta[name="csrf-token"]')?.content || ''
    );
}

function initStarPicker(containerId, inputId) {
    var container = document.getElementById(containerId);
    var input = document.getElementById(inputId);
    if (!container || !input) return;

    var stars = container.querySelectorAll('.ain-star');

    function fill(upTo) {
        stars.forEach(function (s, idx) {
            s.classList.toggle('bi-star-fill', idx < upTo);
            s.classList.toggle('bi-star', idx >= upTo);
        });
    }

    stars.forEach(function (star, idx) {
        star.addEventListener('mouseenter', function () { fill(idx + 1); });
        star.addEventListener('click', function () {
            input.value = idx + 1;
            fill(idx + 1);
        });
    });

    container.addEventListener('mouseleave', function () {
        fill(parseInt(input.value) || 0);
    });

    // Restore saved value on load
    if (input.value) fill(parseInt(input.value));
}

initStarPicker('star-picker', 'rating-input');

// ── Notification read (AJAX) ──────────────────────────────────────
function markNotifRead(id) {
    var csrf = document.querySelector('meta[name="csrf-token"]');
    if (!csrf) return;
    fetch('/notifications/' + id + '/read', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf.content },
    });
}

// ── Nav search with debounce ──────────────────────────────────────
function initNavSearch() {
    var input = document.getElementById('ain-nav-search');
    var resultsDiv = document.getElementById('ain-search-results');
    if (!input || !resultsDiv) return;

    var debounceTimer;

    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        var q = this.value.trim();
        if (q.length < 2) { resultsDiv.style.display = 'none'; return; }

        debounceTimer = setTimeout(function () {
            fetch('/api/search-suggest?q=' + encodeURIComponent(q))
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.results || !data.results.length) {
                        resultsDiv.style.display = 'none';
                        return;
                    }
                    resultsDiv.innerHTML = data.results.map(function (p) {
                        return '<a href="/programs/' + p.slug + '" class="d-block p-2 text-decoration-none text-dark border-bottom small">' + p.title + '</a>';
                    }).join('');
                    resultsDiv.style.display = 'block';
                });
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#ain-nav-search') && !e.target.closest('#ain-search-results')) {
            resultsDiv.style.display = 'none';
        }
    });
}
