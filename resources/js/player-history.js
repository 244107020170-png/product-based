/**
 * player-history.js
 * Interactive behaviour for the Histori Booking page.
 */

const ready = (callback) => {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback, { once: true });
        return;
    }
    callback();
};

ready(() => {
    /* ── Sidebar toggle (shared with dashboard) ── */
    const sidebar        = document.querySelector('[data-sidebar]');
    const sidebarOpen    = document.querySelector('[data-sidebar-open]');
    const sidebarClose   = document.querySelector('[data-sidebar-close]');
    const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');

    const setSidebarState = (open) => {
        if (!sidebar) return;
        sidebar.classList.toggle('is-open', open);
        if (sidebarBackdrop) sidebarBackdrop.classList.toggle('is-visible', open);
    };

    sidebarOpen?.addEventListener('click',   () => setSidebarState(true));
    sidebarClose?.addEventListener('click',  () => setSidebarState(false));
    sidebarBackdrop?.addEventListener('click', () => setSidebarState(false));

    /* ── Stat-card filter tabs ── */
    const statCards = Array.from(document.querySelectorAll('[data-stat-filter]'));
    const bookingCards = Array.from(document.querySelectorAll('[data-booking-status]'));
    const emptyState = document.querySelector('[data-history-empty]');

    const applyStatFilter = (value) => {
        statCards.forEach(c => c.classList.toggle('is-active', c.dataset.statFilter === value));

        let visible = 0;
        bookingCards.forEach(card => {
            const match = value === 'semua' || card.dataset.bookingStatus === value;
            card.hidden = !match;
            if (match) visible++;
        });

        // Only show empty state if there are NO visible cards
        // AND there are booking cards in total (meaning initial data existed)
        if (emptyState) {
            const shouldHide = visible > 0 || bookingCards.length === 0;
            emptyState.hidden = shouldHide;
        }
    };

    statCards.forEach(card => {
        card.addEventListener('click', () => applyStatFilter(card.dataset.statFilter));
    });

    /* ── Dropdown filters (Waktu / Harga) ── */
    const dropdowns = Array.from(document.querySelectorAll('[data-hfilter]'));

    dropdowns.forEach(dropdown => {
        const btn   = dropdown.querySelector('[data-hfilter-btn]');
        const menu  = dropdown.querySelector('[data-hfilter-menu]');
        const label = dropdown.querySelector('[data-hfilter-label]');
        const tags  = Array.from(dropdown.querySelectorAll('[data-hfilter-tag]'));

        if (!btn || !menu) return;

        const toggle = (open) => {
            dropdown.classList.toggle('is-open', open);
            menu.hidden = !open;
            btn.setAttribute('aria-expanded', String(open));
        };

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = dropdown.classList.contains('is-open');
            // Close all other dropdowns first
            dropdowns.forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('is-open');
                    const m = d.querySelector('[data-hfilter-menu]');
                    const b = d.querySelector('[data-hfilter-btn]');
                    if (m) m.hidden = true;
                    if (b) b.setAttribute('aria-expanded', 'false');
                }
            });
            toggle(!isOpen);
        });

        tags.forEach(tag => {
            tag.addEventListener('click', () => {
                tags.forEach(t => t.classList.remove('is-active'));
                tag.classList.add('is-active');
                // Update label in button
                if (label) label.textContent = tag.textContent.trim();
                toggle(false);
                // Submit form to re-query
                const form = document.querySelector('[data-history-form]');
                if (form) {
                    // Update hidden input
                    const inputName = dropdown.dataset.hfilter;
                    let hidden = form.querySelector(`input[name="${inputName}"]`);
                    if (!hidden) {
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = inputName;
                        form.appendChild(hidden);
                    }
                    hidden.value = tag.dataset.hfilterTag;
                    form.submit();
                }
            });
        });
    });

    // Close dropdowns on outside click
    document.addEventListener('click', () => {
        dropdowns.forEach(d => {
            d.classList.remove('is-open');
            const m = d.querySelector('[data-hfilter-menu]');
            const b = d.querySelector('[data-hfilter-btn]');
            if (m) m.hidden = true;
            if (b) b.setAttribute('aria-expanded', 'false');
        });
    });

    /* ── Stat cards click → update URL filter ── */
    statCards.forEach(card => {
        card.addEventListener('click', () => {
            const val = card.dataset.statFilter;
            const form = document.querySelector('[data-history-form]');
            if (form) {
                let hidden = form.querySelector('input[name="status"]');
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'status';
                    form.appendChild(hidden);
                }
                hidden.value = val;
                form.submit();
            }
        });
    });

    /* ── Animate cards on load ── */
    bookingCards.forEach((card, i) => {
        card.style.animationDelay = `${i * 0.06}s`;
    });

    /* ── Initial active stat based on URL ── */
    const urlParams  = new URLSearchParams(window.location.search);
    const initStatus = urlParams.get('status') || 'semua';
    
    // Apply once on load so empty-state visibility is always in sync
    applyStatFilter(initStatus);
});
