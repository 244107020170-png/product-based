<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal & Slot</title>

    @vite(['resources/css/app.css', 'resources/css/owner-jadwal-slot.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>

<div class="dashboard-layout">

    @include('owner.navbar')

    <main class="main-content">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Cari pemesanan, pelanggan...">
            </div>
            <div class="topbar-right">
                <a href="{{ route('owner.notifikasi') }}" class="notif-btn" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;position:relative;">
                    <i class="fa-solid fa-bell"></i>
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span style="position:absolute;top:2px;right:2px;width:10px;height:10px;background:#ef4444;border:2px solid #fff;border-radius:50%;"></span>
                    @endif
                </a>
                <button class="notif-btn" onclick="toggleFaqPopup()">
                    <i class="fa-solid fa-headset"></i>
                </button>
                <a href="{{ route('owner.bantuan') }}" class="notif-btn question" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                    <i class="fa-solid fa-circle-question"></i>
                </a>
                <a href="#" class="profile-box" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="text-decoration:none;color:inherit;">
                    <div>
                        <h5>{{ auth()->user()->name }}</h5>
                        <p>Profil Pemilik</p>
                    </div>
                    <img src="https://i.pravatar.cc/100" alt="Profil">
                </a>
            </div>
        </div>

        {{-- WELCOME --}}
        <div class="welcome-section">
            <div>
                <h1>Jadwal & Slot</h1>
                <p>Atur jadwal operasional dan ketersediaan slot lapangan.</p>
            </div>
            <a href="javascript:void(0)" class="add-btn" onclick="openAddSlotModal()">
                <i class="fa-solid fa-plus"></i>
                Tambah Slot
            </a>
        </div>

        {{-- FILTER BAR --}}
        <div class="schedule-wrapper">

            <div class="schedule-filter-card">
                <div class="filter-left">
                    <div class="filter-input">
                        <i class="fa-solid fa-sliders"></i>
                        <select id="filter-field">
                            <option value="">Filter Lapangan</option>
                        </select>
                    </div>
                    <div class="filter-input">
                        <i class="fa-solid fa-basketball"></i>
                        <select id="filter-sport">
                            <option value="">Jenis Olahraga</option>
                        </select>
                    </div>
                    <div class="filter-input">
                        <i class="fa-regular fa-calendar"></i>
                        <input type="date" id="filter-date">
                    </div>
                </div>
                <button id="reset-filter" class="reset-btn">
                    <i class="fa-solid fa-rotate-right"></i>
                    Atur Ulang Filter
                </button>
            </div>

            {{-- NAVIGATION --}}
            <div class="schedule-nav-card">
                <div class="week-navigation">
                    <button id="prev-week" class="nav-week-btn">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <div id="week-label" class="week-label"></div>
                    <button id="next-week" class="nav-week-btn">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
                <div class="field-title">
                    <i class="fa-solid fa-location-dot"></i>
                    <span id="field-name-header"></span>
                    <span id="field-courts-badge" class="courts-badge"></span>
                </div>
            </div>

            {{-- LINE TABS --}}
            <div class="js-tabs">
                <button class="js-tab is-active" data-tab="jadwal">Jadwal</button>
                <button class="js-tab" data-tab="slot">Slot Lapangan</button>
                <div class="js-tabs-baseline"></div>
                <div id="js-tab-indicator" class="js-tab-indicator"></div>
            </div>

            {{-- TAB: JADWAL (Overview) --}}
            <div class="js-tabpanel is-active" data-panel="jadwal">
                <div class="schedule-table-card">
                    <div class="table-scroll">
                        <div id="table-jadwal"></div>
                    </div>
                </div>
            </div>

            {{-- TAB: SLOT LAPANGAN (Per-Court) --}}
            <div class="js-tabpanel" data-panel="slot">
                <div class="court-selector" id="court-selector"></div>
                <div class="schedule-table-card">
                    <div class="table-scroll">
                        <div id="table-slot"></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- TOAST --}}
        <div id="toastContainer" class="toast-container"></div>

        {{-- SAVE BAR --}}
        <div id="saveBar" class="save-bar">
            <p>Ada <strong id="changeCount">0</strong> perubahan yang belum disimpan</p>
            <button class="save-btn" onclick="saveAllChanges()">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </div>

        {{-- MODAL TAMBAH SLOT --}}
        <div id="addSlotModal" class="modal-overlay">
            <div class="modal-box">
                <div class="modal-header">
                    <h2><i class="fa-solid fa-plus" style="color:#e53935;margin-right:8px;"></i> Tambah Slot</h2>
                    <button class="modal-close" onclick="closeAddSlotModal()">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Lapangan</label>
                        <select id="modal-field"></select>
                    </div>
                    <div class="form-group">
                        <label>Nomor Lapangan</label>
                        <select id="modal-court"></select>
                    </div>
                    <div class="form-group">
                        <label>Pilih Tanggal</label>
                        <input type="date" id="modal-date">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Jam Mulai</label>
                            <select id="modal-start-hour"></select>
                        </div>
                        <div class="form-group">
                            <label>Jam Selesai</label>
                            <select id="modal-end-hour"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status Slot</label>
                        <select id="modal-status">
                            <option value="tersedia">Tersedia</option>
                            <option value="tutup">Tidak Tersedia</option>
                            <option value="perbaikan">Perbaikan</option>
                        </select>
                    </div>
                    <div id="modal-error" class="form-error"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary" onclick="closeAddSlotModal()">Batal</button>
                    <button class="btn-primary" onclick="submitAddSlot()">Simpan</button>
                </div>
            </div>
        </div>

    </main>

</div>

<script>
    // ── Data ──
    const fields = @json($fields);
    const sportTypes = ['Semua Olahraga', ...new Set(fields.map(f => f.type).filter(Boolean))];

    let slotData = {};
    let holidayDates = [];
    let pendingChanges = {};
    let pendingHolidays = {};
    let hasUnsavedChanges = false;
    let currentDate = new Date();

    let activeTab = 'jadwal';
    let selectedCourt = 1;

    // ── Helpers ──
    function toDateStr(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + d;
    }

    function getWeekDates(baseDate) {
        const d = new Date(baseDate);
        const day = d.getDay();
        const diff = d.getDate() - day + (day === 0 ? -6 : 1);
        d.setDate(diff);
        const dates = [];
        for (let i = 0; i < 7; i++) {
            const date = new Date(d);
            date.setDate(date.getDate() + i);
            dates.push(date);
        }
        return dates;
    }

    function getDayName(date) {
        const names = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        return names[date.getDay()];
    }

    function getMonthName(date) {
        const names = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return names[date.getMonth()];
    }

    function normalizeDate(dateVal) {
        const d = new Date(dateVal);
        if (isNaN(d.getTime())) return String(dateVal).substring(0, 10);
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + day;
    }

    function statusLabel(status) {
        const map = { tersedia: 'Tersedia', dibooking: 'Dibooking', perbaikan: 'Perbaikan', tutup: 'Tidak Tersedia' };
        return map[status] || status;
    }

    function getSelectedFieldId() {
        return parseInt(document.getElementById('filter-field').value) || (fields.length ? fields[0].id : 0);
    }

    function getSelectedField() {
        const fid = getSelectedFieldId();
        return fields.find(f => f.id === fid) || null;
    }

    function slotKey(fieldId, courtNumber, dateStr, hour) {
        return fieldId + '-' + courtNumber + '-' + dateStr + '-' + hour;
    }

    // ── Effective state ──
    function isEffectiveHoliday(fieldId, dateStr) {
        const hKey = fieldId + '-' + dateStr;
        if (pendingHolidays[hKey] !== undefined) return pendingHolidays[hKey];
        return holidayDates.indexOf(dateStr) !== -1;
    }

    function getEffectiveStatus(fieldId, courtNumber, dateStr, hour) {
        if (isEffectiveHoliday(fieldId, dateStr)) return 'tutup';
        const key = slotKey(fieldId, courtNumber, dateStr, hour);
        if (pendingChanges[key] !== undefined) {
            if (pendingChanges[key] === '__delete__') return null;
            return pendingChanges[key];
        }
        if (slotData[key] !== undefined) return slotData[key];
        return null;
    }

    function slotExists(fieldId, courtNumber, dateStr, hour) {
        const key = slotKey(fieldId, courtNumber, dateStr, hour);
        if (pendingChanges[key] !== undefined) return pendingChanges[key] !== '__delete__';
        return slotData[key] !== undefined;
    }

    function hasAnySlotWithStatus(fieldId, dateStr, hour, status) {
        const field = fields.find(f => f.id === fieldId);
        if (!field) return false;
        for (let c = 1; c <= field.number_of_courts; c++) {
            const st = getEffectiveStatus(fieldId, c, dateStr, hour);
            if (st === status) return true;
        }
        return false;
    }

    // ── Dirty tracking ──
    function markDirty() {
        if (!hasUnsavedChanges) {
            hasUnsavedChanges = true;
            document.getElementById('saveBar').classList.add('is-visible');
        }
        updateChangeCount();
    }

    function updateChangeCount() {
        const count = Object.keys(pendingChanges).length + Object.keys(pendingHolidays).length;
        document.getElementById('changeCount').textContent = count;
    }

    function clearDirty() {
        hasUnsavedChanges = false;
        pendingChanges = {};
        pendingHolidays = {};
        document.getElementById('saveBar').classList.remove('is-visible');
    }

    // ── Loading ──
    function loadSlotData(fieldId, callback) {
        fetch('/owner/slots/data?field_id=' + fieldId + '&_=' + Date.now())
            .then(r => r.json())
            .then(data => {
                slotData = {};
                holidayDates = [];
                data.slots.forEach(s => {
                    const dateStr = normalizeDate(s.date);
                    const cn = s.court_number || 1;
                    const key = slotKey(s.field_id, cn, dateStr, s.hour);
                    slotData[key] = s.status;
                });
                data.holidays.forEach(h => {
                    const dateStr = normalizeDate(h.date);
                    holidayDates.push(dateStr);
                });
                if (callback) callback();
            })
            .catch(() => {
                slotData = {};
                holidayDates = [];
                if (callback) callback();
            });
    }

    // ── Filters ──
    function initFilters() {
        const fieldSelect = document.getElementById('filter-field');
        if (fields.length === 0) {
            document.getElementById('table-jadwal').innerHTML = '<div style="text-align:center;padding:40px;color:#888;">Belum ada lapangan. <a href="{{ route('owner.tambahLapangan') }}" style="color:#e52d2d;">Tambah sekarang</a></div>';
            return;
        }
        fieldSelect.innerHTML = fields.map(f =>
            '<option value="' + f.id + '">' + f.name + '</option>'
        ).join('');

        const sportSelect = document.getElementById('filter-sport');
        sportSelect.innerHTML = sportTypes.map(t =>
            '<option value="' + t + '">' + t + '</option>'
        ).join('');

        document.getElementById('filter-date').value = toDateStr(currentDate);
    }

    function applyFilters() {
        const dateVal = document.getElementById('filter-date').value;
        if (dateVal) currentDate = new Date(dateVal + 'T00:00:00');

        const sportVal = document.getElementById('filter-sport').value;
        const fieldSelect = document.getElementById('filter-field');
        if (sportVal && sportVal !== 'Semua Olahraga') {
            const filtered = fields.filter(f => f.type === sportVal);
            fieldSelect.innerHTML = filtered.map(f =>
                '<option value="' + f.id + '">' + f.name + '</option>'
            ).join('');
            if (fieldSelect.options.length > 0) fieldSelect.value = filtered[0].id;
        } else {
            fieldSelect.innerHTML = fields.map(f =>
                '<option value="' + f.id + '">' + f.name + '</option>'
            ).join('');
        }

        const fieldId = getSelectedFieldId();
        loadSlotData(fieldId, renderActiveTab);
    }

    function prevWeek() {
        currentDate.setDate(currentDate.getDate() - 7);
        document.getElementById('filter-date').value = toDateStr(currentDate);
        applyFilters();
    }

    function nextWeek() {
        currentDate.setDate(currentDate.getDate() + 7);
        document.getElementById('filter-date').value = toDateStr(currentDate);
        applyFilters();
    }

    function resetFilter() {
        currentDate = new Date();
        document.getElementById('filter-date').value = toDateStr(currentDate);
        if (fields.length > 0) document.getElementById('filter-field').value = fields[0].id;
        document.getElementById('filter-sport').value = 'Semua Olahraga';
        const fieldId = getSelectedFieldId();
        loadSlotData(fieldId, renderActiveTab);
    }

    function renderActiveTab() {
        if (activeTab === 'jadwal') {
            renderJadwal();
        } else {
            renderSlotLapangan();
        }
    }

    // ── RENDER: TAB JADWAL (Overview) ──
    function renderJadwal() {
        const weekDates = getWeekDates(currentDate);
        const field = getSelectedField();
        const fieldId = getSelectedFieldId();

        if (!field) {
            document.getElementById('table-jadwal').innerHTML = '<div style="text-align:center;padding:40px;color:#888;">Pilih lapangan terlebih dahulu.</div>';
            return;
        }

        updateWeekHeader(field);

        const openHour = parseInt(field.open_time?.split(':')[0] || '08');
        const closeHour = parseInt(field.close_time?.split(':')[0] || '22');
        const hours = [];
        for (let h = openHour; h <= closeHour; h++) hours.push(h);
        const numCourts = field.number_of_courts;

        let html = '<table class="schedule-table">';
        html += '<thead><tr>';
        html += '<th>WAKTU</th>';
        for (let i = 0; i < weekDates.length; i++) {
            const date = weekDates[i];
            const dateStr = toDateStr(date);
            const isHoliday = isEffectiveHoliday(fieldId, dateStr);
            const holidayClass = isHoliday ? 'th-holiday' : '';
            html += '<th class="' + holidayClass + '">';
            html += '<div class="table-head-date">';
            html += '<span>' + getDayName(date) + ', ' + date.getDate() + ' ' + getMonthName(date) + '</span>';
            html += '<button class="toggle-holiday ' + (isHoliday ? 'is-holiday' : '') + '" data-date="' + dateStr + '" title="' + (isHoliday ? 'Hapus libur' : 'Tandai libur') + '">';
            html += '<i class="fa-solid ' + (isHoliday ? 'fa-lock' : 'fa-unlock') + '"></i> ';
            html += isHoliday ? 'Libur' : 'Tandai Libur';
            html += '</button>';
            html += '</div></th>';
        }
        html += '</tr></thead>';

        html += '<tbody>';
        for (let r = 0; r < hours.length; r++) {
            const hour = hours[r];
            html += '<tr>';
            html += '<td class="time-column">' + String(hour).padStart(2, '0') + '.00</td>';

            for (let c = 0; c < weekDates.length; c++) {
                const dateStr = toDateStr(weekDates[c]);

                if (isEffectiveHoliday(fieldId, dateStr)) {
                    if (r === 0) {
                        html += '<td rowspan="' + hours.length + '" style="padding:0;border-left:1px solid #f3f4f6;vertical-align:middle;background:#fef2f2;">';
                        html += '<div class="locked-day">';
                        html += '<i class="fa-solid fa-lock locked-icon"></i>';
                        html += '<span style="font-size:0.8rem;font-weight:600;color:#dc2626;">Tutup</span>';
                        html += '<span style="font-size:0.65rem;color:#9ca3af;">Tanggal Merah</span>';
                        html += '</div></td>';
                    }
                    continue;
                }

                // Build per-court status for this hour
                let courtStatuses = [];
                for (let cn = 1; cn <= numCourts; cn++) {
                    courtStatuses.push({
                        court: cn,
                        status: getEffectiveStatus(fieldId, cn, dateStr, hour)
                    });
                }

                // Determine summary
                const bookedCourts = courtStatuses.filter(s => s.status === 'dibooking').map(s => s.court);
                const maintenanceCourts = courtStatuses.filter(s => s.status === 'perbaikan').map(s => s.court);
                const closedCourts = courtStatuses.filter(s => s.status === 'tutup').map(s => s.court);
                const availableCourts = courtStatuses.filter(s => s.status === 'tersedia' || s.status === null).length;
                const totalCourts = numCourts;
                const usedCourts = bookedCourts.length + maintenanceCourts.length + closedCourts.length;

                html += '<td style="padding:0.5rem;border-left:1px solid #f3f4f6;vertical-align:top;">';
                html += '<div class="jadwal-cell">';

                if (usedCourts === 0) {
                    // All available
                    html += '<div class="jadwal-status status-tersedia" style="min-height:60px;display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:12px;">';
                    html += '<strong>' + String(hour).padStart(2, '0') + '.00 - ' + String(hour + 1).padStart(2, '0') + '.00</strong>';
                    html += '<span>Tersedia</span>';
                    html += '<span style="font-size:10px;color:#15803d;margin-top:2px;">' + totalCourts + '/' + totalCourts + ' lapangan</span>';
                    html += '</div>';
                } else if (usedCourts === totalCourts && maintenanceCourts.length === totalCourts) {
                    // All maintenance
                    html += '<div class="jadwal-status status-perbaikan" style="min-height:60px;display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:12px;">';
                    html += '<strong>' + String(hour).padStart(2, '0') + '.00 - ' + String(hour + 1).padStart(2, '0') + '.00</strong>';
                    html += '<span>Perbaikan</span>';
                    html += '</div>';
                } else if (usedCourts === totalCourts) {
                    // All used (penuh)
                    html += '<div class="jadwal-status" style="min-height:60px;display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:12px;background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;">';
                    html += '<strong>' + String(hour).padStart(2, '0') + '.00 - ' + String(hour + 1).padStart(2, '0') + '.00</strong>';
                    html += '<span>Penuh</span>';
                    html += '<span style="font-size:10px;margin-top:2px;">Semua ' + totalCourts + ' lapangan digunakan</span>';
                    html += '</div>';
                } else {
                    // Mixed
                    let infoLines = [];
                    if (bookedCourts.length > 0) {
                        infoLines.push('<span style="color:#2563eb;"><strong>Dibooking:</strong> ' + bookedCourts.map(c => 'Lp ' + c).join(', ') + '</span>');
                    }
                    if (maintenanceCourts.length > 0) {
                        infoLines.push('<span style="color:#a16207;"><strong>Perbaikan:</strong> ' + maintenanceCourts.map(c => 'Lp ' + c).join(', ') + '</span>');
                    }
                    if (closedCourts.length > 0) {
                        infoLines.push('<span style="color:#6b7280;"><strong>Tutup:</strong> ' + closedCourts.map(c => 'Lp ' + c).join(', ') + '</span>');
                    }
                    html += '<div class="jadwal-status status-tersedia" style="min-height:60px;border-radius:12px;">';
                    html += '<strong>' + String(hour).padStart(2, '0') + '.00 - ' + String(hour + 1).padStart(2, '0') + '.00</strong>';
                    html += '<div style="font-size:11px;line-height:1.5;margin-top:4px;">' + infoLines.join('<br>') + '</div>';
                    html += '<span style="font-size:10px;color:#15803d;margin-top:2px;display:block;">Sisa: ' + availableCourts + '/' + totalCourts + ' lapangan</span>';
                    html += '</div>';
                }

                html += '</div></td>';
            }
            html += '</tr>';
        }
        html += '</tbody></table>';

        document.getElementById('table-jadwal').innerHTML = html;
    }

    // ── RENDER: TAB SLOT LAPANGAN (Per-Court) ──
    function updateWeekHeader(field) {
        const weekDates = getWeekDates(currentDate);
        const start = weekDates[0];
        const end = weekDates[6];
        document.getElementById('week-label').textContent =
            start.getDate() + ' - ' + end.getDate() + ' ' + getMonthName(start) + ' ' + start.getFullYear();
        document.getElementById('field-name-header').textContent = field.name;
        const badge = document.getElementById('field-courts-badge');
        if (badge) badge.textContent = field.number_of_courts + ' Lapangan';
    }

    function renderSlotLapangan() {
        const field = getSelectedField();
        const fieldId = getSelectedFieldId();

        if (!field) {
            document.getElementById('table-slot').innerHTML = '<div style="text-align:center;padding:40px;color:#888;">Pilih lapangan terlebih dahulu.</div>';
            return;
        }

        updateWeekHeader(field);

        // Validate selectedCourt
        if (selectedCourt > field.number_of_courts) selectedCourt = 1;

        // Render court selector
        const numCourts = field.number_of_courts;
        let courtHtml = '<div class="court-list">';
        for (let cn = 1; cn <= numCourts; cn++) {
            const active = cn === selectedCourt ? 'active' : '';
            courtHtml += '<button class="court-btn ' + active + '" data-court="' + cn + '">Lapangan ' + cn + '</button>';
        }
        courtHtml += '</div>';
        document.getElementById('court-selector').innerHTML = courtHtml;

        // Render schedule for selected court
        renderCourtSchedule(field, fieldId);
    }

    function renderCourtSchedule(field, fieldId) {
        const weekDates = getWeekDates(currentDate);
        const cn = selectedCourt;
        const openHour = parseInt(field.open_time?.split(':')[0] || '08');
        const closeHour = parseInt(field.close_time?.split(':')[0] || '22');
        const hours = [];
        for (let h = openHour; h <= closeHour; h++) hours.push(h);

        let html = '<table class="schedule-table">';
        html += '<thead><tr>';
        html += '<th>WAKTU</th>';
        for (let i = 0; i < weekDates.length; i++) {
            const date = weekDates[i];
            const dateStr = toDateStr(date);
            const isHoliday = isEffectiveHoliday(fieldId, dateStr);
            const holidayClass = isHoliday ? 'th-holiday' : '';
            html += '<th class="' + holidayClass + '">';
            html += '<div class="table-head-date">';
            html += '<span>' + getDayName(date) + ', ' + date.getDate() + ' ' + getMonthName(date) + '</span>';
            html += '<button class="toggle-holiday ' + (isHoliday ? 'is-holiday' : '') + '" data-date="' + dateStr + '" title="' + (isHoliday ? 'Hapus libur' : 'Tandai libur') + '">';
            html += '<i class="fa-solid ' + (isHoliday ? 'fa-lock' : 'fa-unlock') + '"></i> ';
            html += isHoliday ? 'Libur' : 'Tandai Libur';
            html += '</button>';
            html += '</div></th>';
        }
        html += '</tr></thead>';

        html += '<tbody>';
        for (let r = 0; r < hours.length; r++) {
            const hour = hours[r];
            html += '<tr>';
            html += '<td class="time-column">' + String(hour).padStart(2, '0') + '.00</td>';

            for (let c = 0; c < weekDates.length; c++) {
                const dateStr = toDateStr(weekDates[c]);

                if (isEffectiveHoliday(fieldId, dateStr)) {
                    if (r === 0) {
                        html += '<td rowspan="' + hours.length + '" style="padding:0;border-left:1px solid #f3f4f6;vertical-align:middle;background:#fef2f2;">';
                        html += '<div class="locked-day">';
                        html += '<i class="fa-solid fa-lock locked-icon"></i>';
                        html += '<span style="font-size:0.8rem;font-weight:600;color:#dc2626;">Tutup</span>';
                        html += '<span style="font-size:0.65rem;color:#9ca3af;">Tanggal Merah</span>';
                        html += '</div></td>';
                    }
                    continue;
                }

                const status = getEffectiveStatus(fieldId, cn, dateStr, hour);
                const key = slotKey(fieldId, cn, dateStr, hour);
                const isDeleted = pendingChanges[key] === '__delete__';

                if (isDeleted) {
                    html += '<td style="padding:0.5rem;border-left:1px solid #f3f4f6;vertical-align:top;">';
                    html += '<div class="slot-status" style="background:#f9fafb;border:1px dashed #d1d5db;justify-content:center;align-items:center;min-height:70px;cursor:pointer;" onclick="restoreSlot(\'' + key + '\')" title="Kembalikan slot">';
                    html += '<span style="font-size:11px;font-weight:600;color:#9ca3af;">Dihapus</span>';
                    html += '<span style="font-size:10px;color:#3b82f6;display:block;margin-top:4px;"><i class="fa-solid fa-rotate-left"></i> Kembalikan</span>';
                    html += '</div></td>';
                } else {
                    const stClass = status ? 'status-' + status : 'status-tersedia';
                    html += '<td style="padding:0.5rem;border-left:1px solid #f3f4f6;vertical-align:top;">';
                    html += '<div class="slot-status ' + stClass + '" data-slot-key="' + key + '" data-field-id="' + fieldId + '" data-court="' + cn + '" data-date="' + dateStr + '" data-hour="' + hour + '">';
                    html += '<div><strong>' + String(hour).padStart(2, '0') + '.00 - ' + String(hour + 1).padStart(2, '0') + '.00</strong><br>' + statusLabel(status || 'tersedia') + '</div>';
                    html += '<div class="slot-actions">';
                    html += '<i class="fa-solid fa-ellipsis slot-toggle"></i>';
                    html += '<div class="slot-menu">';
                    html += '<span data-action="tersedia" class="slot-menu-item">Tersedia</span>';
                    html += '<span data-action="dibooking" class="slot-menu-item">Dibooking</span>';
                    html += '<span data-action="perbaikan" class="slot-menu-item">Perbaikan</span>';
                    html += '<span data-action="tutup" class="slot-menu-item">Tidak Tersedia</span>';
                    html += '<span data-action="hapus" class="slot-menu-item" style="color:#ef4444;">Hapus</span>';
                    html += '</div></div></div></td>';
                }
            }
            html += '</tr>';
        }
        html += '</tbody></table>';

        document.getElementById('table-slot').innerHTML = html;
    }

    // ── Tab switching ──
    function switchTab(tab) {
        activeTab = tab;
        document.querySelectorAll('.js-tab').forEach(t => {
            t.classList.toggle('is-active', t.dataset.tab === tab);
        });
        document.querySelectorAll('.js-tabpanel').forEach(p => {
            p.classList.toggle('is-active', p.dataset.panel === tab);
        });
        const indicator = document.getElementById('js-tab-indicator');
        if (indicator) {
            indicator.style.left = tab === 'jadwal' ? '0' : '50%';
        }
        renderActiveTab();
    }

    // ── Actions ──
    function toggleHoliday(dateStr) {
        const fieldId = getSelectedFieldId();
        const hKey = fieldId + '-' + dateStr;
        const currentlyHoliday = isEffectiveHoliday(fieldId, dateStr);
        pendingHolidays[hKey] = !currentlyHoliday;
        markDirty();
        renderActiveTab();
    }

    function changeSlotStatus(fieldId, courtNumber, dateStr, hour, action) {
        const key = slotKey(fieldId, courtNumber, dateStr, hour);
        if (action === 'hapus') {
            pendingChanges[key] = '__delete__';
        } else {
            pendingChanges[key] = action;
        }
        markDirty();
        renderActiveTab();
    }

    function restoreSlot(key) {
        if (pendingChanges[key] !== undefined) {
            delete pendingChanges[key];
        }
        markDirty();
        renderActiveTab();
    }

    // ── Modal ──
    function populateModalHours() {
        const fieldId = parseInt(document.getElementById('modal-field').value);
        const field = fields.find(f => f.id === fieldId);
        const openHour = field ? parseInt(field.open_time?.split(':')[0] || '08') : 8;
        const closeHour = field ? parseInt(field.close_time?.split(':')[0] || '22') : 22;

        const startSelect = document.getElementById('modal-start-hour');
        startSelect.innerHTML = '';
        for (let h = openHour; h < closeHour; h++) {
            const val = String(h).padStart(2, '0');
            startSelect.innerHTML += '<option value="' + h + '">' + val + '.00</option>';
        }

        const endSelect = document.getElementById('modal-end-hour');
        endSelect.innerHTML = '';
        for (let h = openHour + 1; h <= closeHour; h++) {
            const val = String(h).padStart(2, '0');
            endSelect.innerHTML += '<option value="' + h + '">' + val + '.00</option>';
        }
        if (openHour + 1 <= closeHour) endSelect.value = openHour + 1;
    }

    function openAddSlotModal() {
        const currentFieldId = getSelectedFieldId();
        const fieldSelect = document.getElementById('modal-field');
        fieldSelect.innerHTML = fields.map(f =>
            '<option value="' + f.id + '">' + f.name + '</option>'
        ).join('');
        if (currentFieldId) fieldSelect.value = currentFieldId;

        updateModalCourtSelect();
        populateModalHours();

        const today = toDateStr(new Date());
        document.getElementById('modal-date').value = today;

        document.getElementById('modal-error').classList.remove('is-visible');
        document.getElementById('addSlotModal').classList.add('is-visible');
    }

    function updateModalCourtSelect() {
        const fieldId = parseInt(document.getElementById('modal-field').value);
        const field = fields.find(f => f.id === fieldId);
        const numCourts = field ? field.number_of_courts : 1;
        const courtSelect = document.getElementById('modal-court');
        courtSelect.innerHTML = '';
        for (let cn = 1; cn <= numCourts; cn++) {
            courtSelect.innerHTML += '<option value="' + cn + '">Lapangan ' + cn + '</option>';
        }
    }

    function closeAddSlotModal() {
        document.getElementById('addSlotModal').classList.remove('is-visible');
        document.getElementById('modal-error').classList.remove('is-visible');
    }

    function submitAddSlot() {
        const fieldId = parseInt(document.getElementById('modal-field').value);
        const courtNumber = parseInt(document.getElementById('modal-court').value);
        const date = document.getElementById('modal-date').value;
        const startHour = parseInt(document.getElementById('modal-start-hour').value);
        const endHour = parseInt(document.getElementById('modal-end-hour').value);
        const status = document.getElementById('modal-status').value;
        const errorEl = document.getElementById('modal-error');

        if (!fieldId || !date) {
            errorEl.textContent = 'Pilih lapangan dan tanggal.';
            errorEl.classList.add('is-visible');
            return;
        }

        if (endHour <= startHour) {
            errorEl.textContent = 'Jam selesai harus lebih besar dari jam mulai.';
            errorEl.classList.add('is-visible');
            return;
        }

        // Conflict check: same field + same court + same date + same hour + same status
        const conflicts = [];
        for (let h = startHour; h < endHour; h++) {
            const existingStatus = getEffectiveStatus(fieldId, courtNumber, date, h);
            if (existingStatus === status) {
                conflicts.push(h);
            }
        }

        if (conflicts.length > 0) {
            const hoursStr = conflicts.map(h => String(h).padStart(2, '0') + '.00').join(', ');
            errorEl.textContent = 'Bentrok pada: ' + hoursStr + ' (status ' + statusLabel(status) + ' sudah ada)';
            errorEl.classList.add('is-visible');
            return;
        }

        for (let h = startHour; h < endHour; h++) {
            const key = slotKey(fieldId, courtNumber, date, h);
            pendingChanges[key] = status;
        }

        errorEl.classList.remove('is-visible');
        closeAddSlotModal();
        markDirty();
        showToast((endHour - startHour) + ' slot berhasil ditambahkan.', 'success');
    }

    // ── Save ──
    function saveAllChanges() {
        const btn = document.querySelector('.save-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

        const slotsPayload = [];
        for (const [key, status] of Object.entries(pendingChanges)) {
            const parts = key.split('-');
            const fid = parseInt(parts[0]);
            const cn = parseInt(parts[1]);
            const date = parts.slice(2, 4).join('-');
            // Wait, the format is "fieldId-courtNumber-YYYY-MM-DD-hour"
            // parts[0] = fieldId, parts[1] = courtNumber, parts[2] = YYYY, parts[3] = MM, parts[4] = DD, parts[5] = hour
            // So date = parts[2] + '-' + parts[3] + '-' + parts[4]
            // hour = parseInt(parts[5])
            const year = parts[2];
            const month = parts[3];
            const day = parts[4];
            const dateStr = year + '-' + month + '-' + day;
            const hour = parseInt(parts[5]);
            slotsPayload.push({
                field_id: fid,
                court_number: cn,
                date: dateStr,
                hour: hour,
                status: status,
                _delete: status === '__delete__',
            });
        }

        const holidaysPayload = [];
        for (const [key, isHoliday] of Object.entries(pendingHolidays)) {
            const firstDash = key.indexOf('-');
            const fid = parseInt(key.substring(0, firstDash));
            const date = key.substring(firstDash + 1);
            holidaysPayload.push({
                field_id: fid,
                date: date,
                is_holiday: isHoliday,
            });
        }

        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch('/owner/slots/save-all', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ slots: slotsPayload, holidays: holidaysPayload }),
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan';
            if (data.success) {
                clearDirty();
                const fieldId = getSelectedFieldId();
                loadSlotData(fieldId, renderActiveTab);
                showToast('Semua perubahan berhasil disimpan!', 'success');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan';
            showToast('Gagal menyimpan perubahan. Silakan coba lagi.', 'error');
        });
    }

    // ── Toast ──
    function showToast(message, type) {
        const container = document.getElementById('toastContainer');
        const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', info: 'fa-circle-info' };
        const toast = document.createElement('div');
        toast.className = 'toast toast-' + type;
        toast.innerHTML = '<i class="fa-solid ' + (icons[type] || icons.info) + '"></i> ' + message;
        container.appendChild(toast);

        setTimeout(function () {
            toast.style.animation = 'toastOut 0.3s ease forwards';
            setTimeout(function () { toast.remove(); }, 300);
        }, 3000);
    }

    // ── beforeunload ──
    window.addEventListener('beforeunload', function (e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // ── Init ──
    document.addEventListener('DOMContentLoaded', function () {
        if (fields.length === 0) {
            document.getElementById('table-jadwal').innerHTML = '<div style="text-align:center;padding:40px;color:#888;">Belum ada lapangan. <a href="{{ route('owner.tambahLapangan') }}" style="color:#e52d2d;">Tambah sekarang</a></div>';
            return;
        }

        initFilters();

        const qFieldId = (function () {
            const params = new URLSearchParams(window.location.search);
            return params.get('field_id');
        })();

        let initialFieldId = fields[0].id;
        if (qFieldId) {
            const qId = parseInt(qFieldId);
            if (fields.some(function (f) { return f.id === qId; })) initialFieldId = qId;
        }
        document.getElementById('filter-field').value = initialFieldId;

        const qCourt = (function () {
            const params = new URLSearchParams(window.location.search);
            return params.get('court');
        })();
        if (qCourt) selectedCourt = parseInt(qCourt);

        loadSlotData(initialFieldId, renderActiveTab);

        // ── Event listeners ──

        document.getElementById('filter-field').addEventListener('change', function () {
            const fid = parseInt(this.value);
            selectedCourt = 1;
            loadSlotData(fid, renderActiveTab);
        });

        document.getElementById('filter-sport').addEventListener('change', applyFilters);
        document.getElementById('filter-date').addEventListener('change', applyFilters);
        document.getElementById('prev-week').addEventListener('click', prevWeek);
        document.getElementById('next-week').addEventListener('click', nextWeek);
        document.getElementById('reset-filter').addEventListener('click', resetFilter);

        // Tabs
        document.querySelectorAll('.js-tab').forEach(tab => {
            tab.addEventListener('click', function () {
                switchTab(this.dataset.tab);
            });
        });

        // Modal field change -> update court and hour options
        document.getElementById('modal-field').addEventListener('change', function () {
            updateModalCourtSelect();
            populateModalHours();
        });

        // Close modal on overlay click
        document.getElementById('addSlotModal').addEventListener('click', function (e) {
            if (e.target === this) closeAddSlotModal();
        });

        document.addEventListener('click', function (e) {
            // Court selector
            const courtBtn = e.target.closest('.court-btn');
            if (courtBtn) {
                e.preventDefault();
                document.querySelectorAll('.court-btn').forEach(b => b.classList.remove('active'));
                courtBtn.classList.add('active');
                selectedCourt = parseInt(courtBtn.dataset.court);
                const field = getSelectedField();
                if (field) renderCourtSchedule(field, field.id);
                return;
            }

            const toggle = e.target.closest('.slot-toggle');
            if (toggle) {
                e.stopPropagation();
                const container = toggle.closest('.slot-actions');
                const wasOpen = container.classList.contains('open');
                document.querySelectorAll('.slot-actions.open').forEach(function (el) {
                    el.classList.remove('open');
                });
                if (!wasOpen) container.classList.add('open');
                return;
            }

            const holidayBtn = e.target.closest('.toggle-holiday');
            if (holidayBtn) {
                e.preventDefault();
                e.stopPropagation();
                toggleHoliday(holidayBtn.dataset.date);
                return;
            }

            const item = e.target.closest('.slot-menu-item');
            if (item) {
                e.stopPropagation();
                const statusEl = item.closest('.slot-status');
                const fieldId = parseInt(statusEl.dataset.fieldId);
                const court = parseInt(statusEl.dataset.court || '1');
                const date = statusEl.dataset.date;
                const hour = parseInt(statusEl.dataset.hour);
                const action = item.dataset.action;
                changeSlotStatus(fieldId, court, date, hour, action);
                document.querySelectorAll('.slot-actions.open').forEach(function (el) {
                    el.classList.remove('open');
                });
                return;
            }

            document.querySelectorAll('.slot-actions.open').forEach(function (el) {
                el.classList.remove('open');
            });
        });
    });
</script>

@include('owner.faq-popup')
</body>
</html>
