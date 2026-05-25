document.addEventListener('DOMContentLoaded', () => {

    /* === DETAIL TOGGLE === */

    const detail = document.querySelector('.booking-detail');
    const closeBtn = detail?.querySelector('.detail-header button');

    const statusHistory = {
        'Menunggu Konfirmasi': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Menunggu konfirmasi', color: '#F29E10' },
        ],
        'Telah Dikonfirmasi': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Menunggu konfirmasi', color: 'black' },
            { label: 'Telah dikonfirmasi', color: '#1b9d59' },
        ],
        'Selesai': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Menunggu konfirmasi', color: 'black' },
            { label: 'Telah dikonfirmasi', color: 'black' },
            { label: 'Selesai', color: '#1b9d59' },
        ],
        'Dibatalkan': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Menunggu konfirmasi', color: 'black' },
            { label: 'Dibatalkan', color: '#dc3d3d' },
        ],
        'Ditolak': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Menunggu konfirmasi', color: 'black' },
            { label: 'Ditolak', color: '#dc3d3d' },
        ],
        'Kadaluarsa': [
            { label: 'Booking dibuat', color: 'black' },
            { label: 'Kadaluarsa', color: '#dc3d3d' },
        ],
    };

    const statusMap = {
        'pending': 'Menunggu Konfirmasi',
        'waiting_payment': 'Menunggu Konfirmasi',
        'paid': 'Telah Dikonfirmasi',
        'confirmed': 'Telah Dikonfirmasi',
        'completed': 'Selesai',
        'cancelled': 'Dibatalkan',
        'rejected': 'Ditolak',
        'expired': 'Kadaluarsa',
    };

    const statusReverseMap = {
        'Menunggu Konfirmasi': 'pending',
        'Telah Dikonfirmasi': 'confirmed',
        'Selesai': 'completed',
        'Dibatalkan': 'cancelled',
        'Ditolak': 'rejected',
        'Kadaluarsa': 'expired',
    };

    function getBadgeClass(status) {
        if (['Telah Dikonfirmasi', 'Selesai', 'Dibayar'].includes(status)) return 'success';
        if (['Menunggu Konfirmasi', 'Menunggu Pembayaran'].includes(status)) return 'warning';
        if (['Dibatalkan', 'Ditolak', 'Kadaluarsa'].includes(status)) return 'danger';
        return 'info';
    }

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

        detail.querySelector('.status-badge').textContent = status;
        detail.querySelector('.status-badge').className = 'status-badge ' + getBadgeClass(status);
        detail.querySelector('.booking-id').textContent = 'Booking ID #' + String(bookingId).padStart(7, '0');
        detail.querySelector('.detail-profile h4').textContent = customerName;
        const pp = detail.querySelectorAll('.detail-profile p');
        if (pp[0]) pp[0].textContent = customerPhone || '-';
        if (pp[1]) pp[1].textContent = customerEmail || '-';
        detail.querySelector('.detail-info div:nth-child(1) strong').textContent = fieldName;
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
            if (e.target.closest('.action-btn')) return;

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

    const editBtn = detail?.querySelector('.edit-btn');
    const saveBtn = detail?.querySelector('.save-btn');
    const cancelBtn = detail?.querySelector('.cancel-btn');

    function updateHistory(status) {
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

    function enterEditMode() {
        editBtn.style.display = 'none';
        saveBtn.style.display = 'flex';
        cancelBtn.style.display = 'flex';

        detail.querySelectorAll('[data-field]').forEach(el => {
            const field = el.dataset.field;

            if (field === 'status') {
                const currentStatus = el.textContent.trim();
                const select = document.createElement('select');
                select.className = 'edit-input edit-select';
                ['Menunggu Konfirmasi', 'Telah Dikonfirmasi', 'Selesai', 'Dibatalkan'].forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s;
                    opt.textContent = s;
                    if (s === currentStatus) opt.selected = true;
                    select.appendChild(opt);
                });
                el.textContent = '';
                el.appendChild(select);
            } else {
                let val = el.textContent.replace(/^Rp/, '').trim();
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'edit-input';
                input.value = val;
                el.textContent = '';
                el.appendChild(input);
            }
        });
    }

    function exitEditMode() {
        editBtn.style.display = 'flex';
        saveBtn.style.display = 'none';
        cancelBtn.style.display = 'none';

        detail.querySelectorAll('[data-field]').forEach(el => {
            if (el.dataset.field === 'status') {
                const sel = el.querySelector('.edit-select');
                if (sel) {
                    const val = sel.value.trim();
                    el.textContent = val;
                    el.className = 'status-badge ' + getBadgeClass(val);
                    updateHistory(val);
                }
            } else {
                const input = el.querySelector('.edit-input');
                if (input) {
                    let val = input.value.trim();
                    if (el.dataset.field === 'harga') val = 'Rp' + val;
                    el.textContent = val;
                }
            }
        });
    }

    function saveEdit() {
        let newStatus = null;

        detail.querySelectorAll('[data-field]').forEach(el => {
            const input = el.querySelector('.edit-input, .edit-select');
            if (input) {
                const field = el.dataset.field;
                let val = input.value.trim();
                if (field === 'harga') val = 'Rp' + val;
                el.textContent = val;
                if (field === 'status') {
                    newStatus = val;
                    el.className = 'status-badge ' + getBadgeClass(val);
                }
            }
        });

        editBtn.style.display = 'flex';
        saveBtn.style.display = 'none';
        cancelBtn.style.display = 'none';

        if (newStatus && currentEditRow) {
            const badge = currentEditRow.querySelector('.status-badge');
            if (badge) {
                badge.textContent = newStatus;
                badge.className = 'status-badge ' + getBadgeClass(newStatus);
            }

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
                }).catch(err => console.error('Gagal update status:', err));
            }
        }

        if (newStatus) updateHistory(newStatus);
    }

    editBtn?.addEventListener('click', enterEditMode);
    saveBtn?.addEventListener('click', saveEdit);
    cancelBtn?.addEventListener('click', exitEditMode);

    /* === FILTER === */

    const filterBtn = document.querySelector('.filter-btn');
    const filterMenu = document.querySelector('.filter-menu');
    const filterOptions = document.querySelectorAll('.filter-option');
    const rows = document.querySelectorAll('.booking-table tbody tr');
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

            rows.forEach(row => {
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
        rows.forEach(row => row.style.display = '');
    });

});
