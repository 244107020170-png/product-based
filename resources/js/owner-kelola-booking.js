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
    };

    function populateDetail(row) {
        if (!detail) return;

        const cells = row.querySelectorAll('td');
        const customerName = cells[1]?.querySelector('h5')?.textContent || '';
        const customerPhone = cells[1]?.querySelector('p')?.textContent || '';
        const fieldName = cells[2]?.querySelector('h5')?.textContent || '';
        const fieldType = cells[2]?.querySelector('p')?.textContent || '';
        const date = cells[3]?.textContent.trim() || '';
        const time = cells[4]?.textContent.trim() || '';
        const duration = cells[5]?.textContent.trim() || '';
        const status = cells[6]?.querySelector('.status-badge')?.textContent.trim() || '';
        const price = cells[7]?.textContent.trim() || '';

        const bookingId = '#' + Math.random().toString(36).substring(2, 10).toUpperCase();

        detail.querySelector('.status-badge').textContent = status;
        detail.querySelector('.status-badge').className = 'status-badge ' + (
            status === 'Telah Dikonfirmasi' || status === 'Selesai' ? 'success' :
            status === 'Menunggu Konfirmasi' ? 'warning' :
            status === 'Dibatalkan' ? 'danger' : ''
        );
        detail.querySelector('.booking-id').textContent = 'Booking ID ' + bookingId;
        detail.querySelector('.detail-profile h4').textContent = customerName;
        detail.querySelectorAll('.detail-profile p')[0].textContent = customerPhone;
        detail.querySelectorAll('.detail-profile p')[1].textContent = customerName.toLowerCase().replace(/\s+/g, '') + '@gmail.com';
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

    function setStatusDisplay(status) {
        const badge = detail.querySelector('.status-badge');
        badge.textContent = status;
        badge.className = 'status-badge ' + (
            status === 'Telah Dikonfirmasi' || status === 'Selesai' ? 'success' :
            status === 'Menunggu Konfirmasi' ? 'warning' :
            status === 'Dibatalkan' ? 'danger' : ''
        );
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
            const input = el.querySelector('.edit-input, .edit-select');
            if (input) {
                const field = el.dataset.field;
                let val = input.value.trim();
                if (field === 'harga') val = 'Rp' + val;
                el.textContent = val;
                if (field === 'status') {
                    el.className = 'status-badge ' + (
                        val === 'Telah Dikonfirmasi' || val === 'Selesai' ? 'success' :
                        val === 'Menunggu Konfirmasi' ? 'warning' :
                        val === 'Dibatalkan' ? 'danger' : ''
                    );
                    updateHistory(val);
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
                    el.className = 'status-badge ' + (
                        val === 'Telah Dikonfirmasi' || val === 'Selesai' ? 'success' :
                        val === 'Menunggu Konfirmasi' ? 'warning' :
                        val === 'Dibatalkan' ? 'danger' : ''
                    );
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
                badge.className = 'status-badge ' + (
                    newStatus === 'Telah Dikonfirmasi' || newStatus === 'Selesai' ? 'success' :
                    newStatus === 'Menunggu Konfirmasi' ? 'warning' :
                    newStatus === 'Dibatalkan' ? 'danger' : ''
                );
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