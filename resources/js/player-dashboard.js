const ready = (callback) => {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback, { once: true });
        return;
    }

    callback();
};

ready(() => {
    const sidebar = document.querySelector('[data-sidebar]');
    const sidebarOpen = document.querySelector('[data-sidebar-open]');
    const sidebarClose = document.querySelector('[data-sidebar-close]');
    const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');

    const setSidebarState = (open) => {
        if (!sidebar) {
            return;
        }

        sidebar.classList.toggle('is-open', open);

        if (sidebarBackdrop) {
            sidebarBackdrop.classList.toggle('is-visible', open);
        }
    };

    sidebarOpen?.addEventListener('click', () => setSidebarState(true));
    sidebarClose?.addEventListener('click', () => setSidebarState(false));
    sidebarBackdrop?.addEventListener('click', () => setSidebarState(false));

    const filterRoot = document.querySelector('[data-filter-root]');
    const filterButton = document.querySelector('[data-filter-button]');
    const filterMenu = document.querySelector('[data-filter-menu]');
    const filterLabel = document.querySelector('[data-filter-label]');
    const filterOptions = Array.from(document.querySelectorAll('[data-filter-option]'));
    const notificationItems = Array.from(document.querySelectorAll('[data-notification-item]'));
    const filterEmpty = document.querySelector('[data-filter-empty]');
    const searchInput = document.querySelector('[data-dashboard-search]');
    const searchableItems = Array.from(document.querySelectorAll('[data-dashboard-searchable]'));

    const applyCombinedVisibility = (element) => {
        const searchVisible = element.dataset.searchVisible !== 'false';
        const filterVisible = element.dataset.filterVisible !== 'false';
        element.classList.toggle('player-dashboard-hidden', !(searchVisible && filterVisible));
    };

    searchableItems.forEach((item) => {
        item.dataset.searchVisible = 'true';
        item.dataset.filterVisible = item.hasAttribute('data-notification-item') ? 'true' : 'true';
    });

    const updateNotificationEmptyState = () => {
        if (!filterEmpty) {
            return;
        }

        const visibleNotifications = notificationItems.filter(
            (item) => !item.classList.contains('player-dashboard-hidden'),
        );

        filterEmpty.hidden = visibleNotifications.length !== 0;
    };

    const applyFilter = (value) => {
        notificationItems.forEach((item) => {
            const visible = value === 'all' || item.dataset.filterValue === value;
            item.dataset.filterVisible = visible ? 'true' : 'false';
            applyCombinedVisibility(item);
        });

        filterOptions.forEach((option) => {
            option.classList.toggle('is-active', option.dataset.filterOption === value);
        });

        if (filterLabel) {
            const activeOption = filterOptions.find((option) => option.dataset.filterOption === value);
            filterLabel.textContent = activeOption ? activeOption.textContent : 'By Day';
        }

        updateNotificationEmptyState();
    };

    filterButton?.addEventListener('click', () => {
        if (!filterRoot || !filterMenu) {
            return;
        }

        const isOpen = filterRoot.classList.toggle('is-open');
        filterMenu.hidden = !isOpen;
        filterButton.setAttribute('aria-expanded', String(isOpen));
    });

    filterOptions.forEach((option) => {
        option.addEventListener('click', () => {
            const value = option.dataset.filterOption || 'all';
            applyFilter(value);

            if (filterRoot && filterMenu && filterButton) {
                filterRoot.classList.remove('is-open');
                filterMenu.hidden = true;
                filterButton.setAttribute('aria-expanded', 'false');
            }
        });
    });

    document.addEventListener('click', (event) => {
        if (!filterRoot || !filterButton || !filterMenu) {
            return;
        }

        if (!filterRoot.contains(event.target)) {
            filterRoot.classList.remove('is-open');
            filterMenu.hidden = true;
            filterButton.setAttribute('aria-expanded', 'false');
        }
    });

    searchInput?.addEventListener('input', () => {
        const keyword = searchInput.value.trim().toLowerCase();

        searchableItems.forEach((item) => {
            const haystack = item.dataset.dashboardSearchable || '';
            const visible = keyword === '' || haystack.includes(keyword);
            item.dataset.searchVisible = visible ? 'true' : 'false';
            applyCombinedVisibility(item);
        });

        updateNotificationEmptyState();
    });

    const ratingRoot = document.querySelector('[data-rating-root]');
    const ratingStars = Array.from(document.querySelectorAll('[data-rating-star]'));
    const ratingLabel = document.querySelector('[data-rating-label]');
    const reviewBookmark = document.querySelector('[data-review-bookmark]');
    const reviewBookmarkStorageKey = 'playerDashboardReviewBookmarked';

    const updateRating = (value) => {
        ratingStars.forEach((star) => {
            const starValue = Number(star.dataset.value || '0');
            star.classList.toggle('is-selected', starValue <= value);
        });

        if (ratingLabel) {
            ratingLabel.textContent = value > 0
                ? `Placeholder rating aktif: ${value}/5`
                : 'Pilih rating untuk placeholder ini';
        }
    };

    ratingStars.forEach((star) => {
        star.addEventListener('click', () => {
            const value = Number(star.dataset.value || '0');
            updateRating(value);
        });
    });

    if (ratingRoot) {
        updateRating(0);
    }

    const updateBookmarkState = (bookmarked) => {
        if (!reviewBookmark) {
            return;
        }

        reviewBookmark.classList.toggle('is-active', bookmarked);
        reviewBookmark.setAttribute('aria-pressed', String(bookmarked));
        reviewBookmark.setAttribute('aria-label', bookmarked ? 'Batalkan simpan review' : 'Simpan review');
    };

    if (reviewBookmark) {
        let isBookmarked = false;

        try {
            isBookmarked = window.localStorage.getItem(reviewBookmarkStorageKey) === 'true';
        } catch (error) {
            isBookmarked = false;
        }

        updateBookmarkState(isBookmarked);

        reviewBookmark.addEventListener('click', () => {
            isBookmarked = !isBookmarked;
            updateBookmarkState(isBookmarked);

            try {
                window.localStorage.setItem(reviewBookmarkStorageKey, String(isBookmarked));
            } catch (error) {
                // Ignore storage errors and keep the UI responsive.
            }
        });
    }

    applyFilter('all');
});
