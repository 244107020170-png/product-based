document.addEventListener('DOMContentLoaded', () => {

    /* === DETAIL TOGGLE === */

    const detail = document.querySelector('.booking-detail');
    const closeBtn = detail?.querySelector('.detail-header button');

    const statusHistory = {
        'Menunggu Pembayaran': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Menunggu pembayaran', color: '#F29E10' },
        ],
        'Menunggu Konfirmasi': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Pembayaran diterima', color: 'black' },
            { label: 'Menunggu konfirmasi', color: '#F29E10' },
        ],
        'Dibayar': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Pembayaran diterima', color: 'black' },
            { label: 'Dibayar', color: '#1b9d59' },
        ],
        'Dikonfirmasi': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Pembayaran diterima', color: 'black' },
            { label: 'Dikonfirmasi', color: '#1b9d59' },
        ],
        'Selesai': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Pembayaran diterima', color: 'black' },
            { label: 'Dikonfirmasi', color: 'black' },
            { label: 'Selesai', color: '#0284c7' },
        ],
        'Dibatalkan': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Dibatalkan', color: '#dc3d3d' },
        ],
        'Ditolak': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Ditolak', color: '#dc3d3d' },
        ],
        'Kadaluarsa': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Kadaluarsa', color: '#dc3d3d' },
        ],
    };

    const statusLabelMap = {
        'pending': 'Menunggu Pembayaran',
        'waiting_payment': 'Menunggu Pembayaran',
        'waiting_confirmation': 'Menunggu Konfirmasi',
        'paid': 'Dibayar',
        'confirmed': 'Dikonfirmasi',
        'completed': 'Selesai',
        'cancelled': 'Dibatalkan',
        'rejected': 'Ditolak',
        'expired': 'Kadaluarsa',
    };

    const statusReverseMap = {
        'Menunggu Pembayaran': 'pending',
        'Menunggu Konfirmasi': 'waiting_confirmation',
        'Dibayar': 'paid',
        'Dikonfirmasi': 'confirmed',
        'Selesai': 'completed',
        'Dibatalkan': 'cancelled',
        'Ditolak': 'rejected',
        'Kadaluarsa': 'expired',
    };

    const editableStatuses = ['Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Dibayar', 'Dikonfirmasi', 'Dibatalkan', 'Ditolak'];

    function getBadgeClass(status) {
        if (status === 'Dibayar' || status === 'Dikonfirmasi') return 'success';
        if (status === 'Selesai') return 'info';
        if (status === 'Menunggu Pembayaran') return 'secondary';
        if (status === 'Menunggu Konfirmasi') return 'warning';
        if (status === 'Dibatalkan') return 'danger';
        if (status === 'Ditolak') return 'danger-dark';
        if (status === 'Kadaluarsa') return 'danger';
        return 'info';
    }

    function showToast(message, type) {
        const container = document.getElementById('toastContainer') || (() => {
            const c = document.createElement('div');
            c.id = 'toastContainer';
            c.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;';
            document.body.appendChild(c);
            return c;
        })();

        const toast = document.createElement('div');
        toast.style.cssText = 'padding:12px 20px;border-radius:8px;color:#fff;font-weight:500;font-size:14px;' +
            'box-shadow:0 4px 12px rgba(0,0,0,0.15);animation:slideIn 0.3s ease;max-width:400px;word-wrap:break-word;' +
            (type === 'success' ? 'background:#16a34a;' : 'background:#dc2626;');

        toast.textContent = message;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.transition = 'opacity 0.3s ease';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    const style = document.createElement('style');
    style.textContent = '@keyframes slideIn{from{opacity:0;transform:translateX(40px)}to{opacity:1;transform:translateX(0)}}';
    document.head.appendChild(style);

    function populateDetail(row) {
        if (!detail) return;

        const customerName = row.dataset.customerName || '';
        const customerPhone = row.dataset.customerPhone || '';
        const customerEmail = row.dataset.customerEmail || '';
        const fieldName = row.dataset.fieldName || '';
        const fieldType = row.dataset.fieldType || '';
        const date = row.dataset.date || '';
        const time = row.dataset.time || '';
        const status = row.dataset.status || '';
        const price = row.dataset.price || '';
        const bookingId = row.dataset.bookingId || '';
        const courtNumber = row.dataset.courtNumber || '';

        detail.querySelector('.status-badge').textContent = status;
        detail.querySelector('.status-badge').className = 'status-badge ' + getBadgeClass(status);
        detail.querySelector('.booking-id').textContent = 'Booking ID #' + String(bookingId).padStart(7, '0');
        detail.querySelector('.detail-profile h4').textContent = customerName;
        const pp = detail.querySelectorAll('.detail-profile p');
        if (pp[0]) pp[0].textContent = customerPhone || '-';
        if (pp[1]) pp[1].textContent = customerEmail || '-';

        const fieldEl = detail.querySelector('.detail-info div:nth-child(1) strong');
        fieldEl.textContent = courtNumber ? fieldName + ' (Lapangan ' + courtNumber + ')' : fieldName;
        detail.querySelector('.detail-info div:nth-child(2) strong').textContent = date;
        detail.querySelector('.detail-info div:nth-child(3) strong').textContent = time;
        detail.querySelector('.detail-info div:nth-child(4) strong').textContent = price;

        const historyList = detail.querySelector('.history-list');
        historyList.innerHTML = '';
        const steps = statusHistory[status] || [];
        steps.forEach(s => {
            const li = document.createElement('li');
            li.textContent = s.label;
            li.style.color = s.color;
            historyList.appendChild(li);
        });
    }

    document.querySelectorAll('.booking-table tbody tr').forEach(row => {
        if (row.querySelector('td[colspan]')) return;

        row.addEventListener('click', (e) => {
            if (e.target.closest('.action-btn, .action-terima, .action-tolak')) return;

            document.querySelectorAll('.booking-table tbody tr').forEach(r => {
                r.classList.remove('active-row');
            });
            row.classList.add('active-row');
        });

        const actionBtn = row.querySelector('.action-btn');
        if (actionBtn) {
            actionBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                exitEditMode();
                currentEditRow = row;
                populateDetail(row);
                detail?.classList.add('show');
            });
        }
    });

    closeBtn?.addEventListener('click', () => {
        detail?.classList.remove('show');
        exitEditMode();
    });

    /* === EDIT === */

    let currentEditRow = null;
    let isSaving = false;

    const editBtn = detail?.querySelector('.edit-btn');
    const saveBtn = detail?.querySelector('.save-btn');
    const cancelBtn = detail?.querySelector('.cancel-btn');

    function enterEditMode() {
        editBtn.style.display = 'none';
        saveBtn.style.display = 'flex';
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fa-solid fa-check"></i> Simpan';
        cancelBtn.style.display = 'flex';

        const statusEl = detail?.querySelector('[data-field="status"]');
        if (!statusEl) return;

        const currentStatus = statusEl.textContent.trim();
        const select = document.createElement('select');
        select.className = 'edit-input edit-select';
        editableStatuses.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s;
            opt.textContent = s;
            if (s === currentStatus) opt.selected = true;
            select.appendChild(opt);
        });
        statusEl.textContent = '';
        statusEl.appendChild(select);
    }

    function exitEditMode(keepDisplay) {
        if (!keepDisplay) {
            editBtn.style.display = 'flex';
            saveBtn.style.display = 'none';
            cancelBtn.style.display = 'none';
        }

        const statusEl = detail?.querySelector('[data-field="status"]');
        if (!statusEl) return;

        const sel = statusEl.querySelector('.edit-select');
        if (sel) {
            const val = sel.value.trim();
            statusEl.textContent = val;
            statusEl.className = 'status-badge ' + getBadgeClass(val);
            updateHistory(val);
        }
    }

    function revertRowStatus(row, originalStatus) {
        const badge = row.querySelector('.status-badge');
        if (badge) {
            badge.textContent = originalStatus;
            badge.className = 'status-badge ' + getBadgeClass(originalStatus);
        }
    }

    function saveEdit() {
        if (isSaving) return;
        isSaving = true;

        const originalRowStatus = currentEditRow ? currentEditRow.querySelector('.status-badge')?.textContent?.trim() : null;

        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
        }

        const statusEl = detail?.querySelector('[data-field="status"]');
        if (!statusEl) { isSaving = false; return; }

        const sel = statusEl.querySelector('.edit-select');
        if (!sel) { isSaving = false; return; }

        const newStatus = sel.value.trim();
        statusEl.textContent = newStatus;
        statusEl.className = 'status-badge ' + getBadgeClass(newStatus);

        editBtn.style.display = 'none';
        saveBtn.style.display = 'flex';
        cancelBtn.style.display = 'none';

        if (currentEditRow) {
            const badge = currentEditRow.querySelector('.status-badge');
            const rowOriginalStatus = badge?.textContent?.trim();
            const rawStatus = statusReverseMap[newStatus] || 'pending';
            const bookingId = currentEditRow.dataset.bookingId;

            if (bookingId) {
                fetch('/owner/bookings/' + bookingId + '/status', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ status: rawStatus }),
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.message || 'Gagal memperbarui status booking.'); });
                    }
                    return response.json();
                })
                .then(data => {
                    showToast('Status booking berhasil diperbarui.', 'success');
                    if (badge) {
                        badge.textContent = newStatus;
                        badge.className = 'status-badge ' + getBadgeClass(newStatus);
                    }
                    currentEditRow.dataset.rawStatus = rawStatus;
                    currentEditRow.dataset.status = newStatus;
                    populateDetail(currentEditRow);
                    exitEditMode();
                })
                .catch(err => {
                    showToast(err.message || 'Gagal memperbarui status booking. Silakan coba lagi.', 'error');
                    if (rowOriginalStatus && currentEditRow) {
                        revertRowStatus(currentEditRow, rowOriginalStatus);
                        currentEditRow.dataset.status = rowOriginalStatus;
                    }
                    exitEditMode(true);
                    editBtn.style.display = 'flex';
                    saveBtn.style.display = 'none';
                    cancelBtn.style.display = 'none';
                })
                .finally(() => {
                    isSaving = false;
                    if (saveBtn) {
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = '<i class="fa-solid fa-check"></i> Simpan';
                    }
                });
            } else {
                isSaving = false;
                exitEditMode();
            }
        } else {
            isSaving = false;
            exitEditMode();
        }

        updateHistory(newStatus);
    }

    editBtn?.addEventListener('click', enterEditMode);
    saveBtn?.addEventListener('click', saveEdit);
    cancelBtn?.addEventListener('click', exitEditMode);

    /* === SEARCH === */

    const searchInput = document.querySelector('.search-box input');
    const allRows = document.querySelectorAll('.booking-table tbody tr');

    searchInput?.addEventListener('input', () => {
        const q = searchInput.value.toLowerCase().trim();

        allRows.forEach(row => {
            if (row.querySelector('td[colspan]')) return;

            if (!q) {
                row.style.display = '';
                return;
            }

            const name = (row.dataset.customerName || '').toLowerCase();
            const phone = (row.dataset.customerPhone || '').toLowerCase();
            const field = (row.dataset.fieldName || '').toLowerCase();
            const status = (row.dataset.status || '').toLowerCase();

            row.style.display = name.includes(q) || phone.includes(q) || field.includes(q) || status.includes(q) ? '' : 'none';
        });
    });

    /* === FORM LOADING STATE === */

    document.querySelectorAll('.action-form').forEach(form => {
        form.addEventListener('submit', () => {
            const btns = form.querySelectorAll('.action-terima, .action-tolak');
            btns.forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            });
        });
    });

    /* === FILTER === */

    const filterBtn = document.querySelector('.filter-btn');
    const filterMenu = document.querySelector('.filter-menu');
    const filterOptions = document.querySelectorAll('.filter-option');
    const resetBtn = document.querySelector('.reset-btn');

    filterBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        filterMenu?.classList.toggle('show');
    });

    document.addEventListener('click', () => {
        filterMenu?.classList.remove('show');
    });

    filterOptions.forEach(opt => {
        opt.addEventListener('click', (e) => {
            e.stopPropagation();

            filterOptions.forEach(o => o.classList.remove('active'));
            opt.classList.add('active');

            const status = opt.dataset.status;

            allRows.forEach(row => {
                if (row.querySelector('td[colspan]')) return;
                const badge = row.querySelector('.status-badge');
                if (status === 'all' || badge?.textContent.trim() === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            filterMenu?.classList.remove('show');
        });
    });

    resetBtn?.addEventListener('click', () => {
        filterOptions.forEach(o => o.classList.remove('active'));
        filterOptions[0]?.classList.add('active');
        allRows.forEach(row => row.style.display = '');
    });

    /* === SORT === */

    const sortBtns = document.querySelectorAll('.sort-btn');
    const tbody = document.querySelector('.booking-table tbody');

    function sortRows(order) {
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => !r.querySelector('td[colspan]'));

        rows.sort((a, b) => {
            const dateA = a.dataset.dateSort || '';
            const dateB = b.dataset.dateSort || '';
            const timeA = a.dataset.timeSort || '';
            const timeB = b.dataset.timeSort || '';

            if (dateA !== dateB) {
                return order === 'terbaru' ? dateB.localeCompare(dateA) : dateA.localeCompare(dateB);
            }
            return order === 'terbaru' ? timeB.localeCompare(timeA) : timeA.localeCompare(timeB);
        });

        rows.forEach(row => tbody.appendChild(row));
    }

    sortBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            sortBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            sortRows(btn.dataset.sort);
        });
    });

});
