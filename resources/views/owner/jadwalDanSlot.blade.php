<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Dan Slot</title>

    @vite(['resources/css/owner-jadwal-slot.css'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>

<div class="dashboard-layout">

    {{-- SIDEBAR --}}
    @include('owner.navbar')

    {{-- MAIN CONTENT --}}
    <main class="main-content">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search bookings, customers...">
            </div>

            <div class="topbar-right">
                <button class="notif-btn">
                    <i class="fa-solid fa-bell"></i>
                </button>

                <button class="notif-btn question">
                    <i class="fa-solid fa-circle-question"></i>
                </button>

                <div class="profile-box">
                    <div>
                        <h5>{{ auth()->user()->name }}</h5>
                        <p>Owner Profile</p>
                    </div>

                    <img src="https://i.pravatar.cc/100" alt="Profile">
                </div>
            </div>
        </div>

        <div class="welcome-section">
            <div>
                <h1>Jadwal & Slot</h1>
                <p>Atur jadwal operasional dan ketersediaan slot lapangan.</p>
            </div>

            {{-- <a class="add-btn" href="javascript:void(0)">
                <i class="fa-solid fa-plus"></i>
                Tambah Slot
            </a> --}}

            <a href="javascript:void(0)"
               class="add-btn">

                <i class="fa-solid fa-plus"></i>

                Tambah Slot

            </a>
        </div>

        {{-- FILTER --}}
        {{-- <div class="card-panel" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; gap: 1rem; flex: 1; max-width: 800px;">
                <select id="filter-field" style="flex: 1; padding: 0.625rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; background-color: #f9fafb;"></select>
                <select id="filter-sport" style="flex: 1; padding: 0.625rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; background-color: #f9fafb;"></select>
                <input type="date" id="filter-date" style="flex: 1; padding: 0.625rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; background-color: #f9fafb;">
            </div>
            <button id="reset-filter" style="color: #e52d2d; border: 1px solid #fca5a5; background: none; padding: 0.625rem 1.25rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; margin-left: 1rem;">
                <i class="fa-solid fa-rotate-right"></i> Reset Filter
            </button>
        </div> --}}

        <div class="schedule-wrapper">

            {{-- FILTER CARD --}}
            <div class="schedule-filter-card">

                <div class="filter-left">

                    {{-- FILTER LAPANGAN --}}
                    <div class="filter-input">

                        <i class="fa-solid fa-sliders"></i>

                        <select id="filter-field">
                            <option value="">
                                Filter Lapangan
                            </option>
                        </select>

                    </div>

                    {{-- FILTER OLAHRAGA --}}
                    <div class="filter-input">

                        <i class="fa-solid fa-basketball"></i>

                        <select id="filter-sport">
                            <option value="">
                                Jenis Olahraga
                            </option>
                        </select>

                    </div>

                    {{-- FILTER DATE --}}
                    <div class="filter-input">

                        <i class="fa-regular fa-calendar"></i>

                        <input type="date"
                               id="filter-date">

                    </div>

                </div>

                {{-- RESET --}}
                <button id="reset-filter"
                        class="reset-btn">

                    <i class="fa-solid fa-rotate-right"></i>

                    Reset Filter

                </button>

            </div>

            {{-- =========================
                WEEK NAVIGATION
            ========================== --}}
            <div class="schedule-nav-card">

                <div class="week-navigation">

                    <button id="prev-week"
                            class="nav-week-btn">

                        <i class="fa-solid fa-chevron-left"></i>

                    </button>

                    <div id="week-label"
                         class="week-label">
                    </div>

                    <button id="next-week"
                            class="nav-week-btn">

                        <i class="fa-solid fa-chevron-right"></i>

                    </button>

                </div>

                {{-- FIELD NAME --}}
                <div class="field-title">

                    <i class="fa-solid fa-location-dot"></i>

                    <span id="field-name-header">
                    </span>

                </div>

            </div>


        {{-- TABLE CONTAINER --}}
        {{-- <div class="card-panel" style="padding: 0; overflow: hidden;">
            <div id="table-header-nav" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #f3f4f6;"> --}}
                {{-- <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <button id="prev-week" style="padding: 0.5rem; border: 1px solid #e5e7eb; background: #fff; border-radius: 0.5rem; cursor: pointer;"><i class="fa-solid fa-chevron-left"></i></button>
                    <span id="week-label" style="padding: 0.5rem 1rem; border: 1px solid #e5e7eb; background: #f9fafb; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500;"></span>
                    <button id="next-week" style="padding: 0.5rem; border: 1px solid #e5e7eb; background: #fff; border-radius: 0.5rem; cursor: pointer;"><i class="fa-solid fa-chevron-right"></i></button>
                </div> --}}
                {{-- <span id="field-name-header" style="font-weight: 600; color: #4b5563; font-size: 0.875rem;"></span>
            </div> --}}

            <div class="schedule-table-card">

                <div class="table-scroll">

                    <div id="table-container"></div>

                </div>

            </div>

            <div style="overflow-x: auto;">
                <div id="table-container"></div>
            </div>
        </div>

    </main>

</div>

<script>
    const fields = [
        { id: 1, name: 'Lapangan A', type: 'Futsal' },
        { id: 2, name: 'Lapangan B', type: 'Basket' },
        { id: 3, name: 'Lapangan C', type: 'Badminton' },
        { id: 4, name: 'Lapangan D', type: 'Futsal' },
    ];

    const sportTypes = ['Semua Olahraga', 'Futsal', 'Basket', 'Badminton'];
    const statusClasses = ['status-tersedia', 'status-dibooking', 'status-perbaikan', 'status-tutup'];
    const statusLabels = ['Tersedia', 'Dibooking', 'Perbaikan', 'Tutup'];

    let holidayDates = [];
    let currentDate = new Date();

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
            date.setDate(d.getDate() + i);
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

    function initFilters() {
        const fieldSelect = document.getElementById('filter-field');
        fieldSelect.innerHTML = fields.map(f =>
            '<option value="' + f.id + '">' + f.name + '</option>'
        ).join('');

        const sportSelect = document.getElementById('filter-sport');
        sportSelect.innerHTML = sportTypes.map(t =>
            '<option value="' + t + '">' + t + '</option>'
        ).join('');

        document.getElementById('filter-date').value = toDateStr(currentDate);
    }

    function renderTable() {
        const weekDates = getWeekDates(currentDate);
        const fieldId = parseInt(document.getElementById('filter-field').value) || fields[0].id;
        const field = fields.find(f => f.id === fieldId);
        const selectedField = field || fields[0];

        const start = weekDates[0];
        const end = weekDates[6];
        document.getElementById('week-label').textContent =
            start.getDate() + ' - ' + end.getDate() + ' ' + getMonthName(start) + ' ' + start.getFullYear();
        document.getElementById('field-name-header').textContent = selectedField.name;

        const hours = [];
        for (let h = 8; h <= 22; h++) hours.push(h);

        let html = '<table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.75rem;">';

        html += '<thead><tr style="background-color: #f9fafb; border-bottom: 1px solid #f3f4f6; color: #6b7280;">';
        html += '<th style="padding: 0.75rem 1rem; width: 100px; min-width: 100px;">WAKTU</th>';
        for (let i = 0; i < weekDates.length; i++) {
            const date = weekDates[i];
            const dateStr = toDateStr(date);
            const isHoliday = holidayDates.indexOf(dateStr) !== -1;
            const bg = isHoliday ? 'background-color: #fef2f2;' : '';
            html += '<th style="padding: 0.75rem 1rem; border-left: 1px solid #f3f4f6; min-width: 130px; ' + bg + '">';
            html += '<div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">';
            html += '<span>' + getDayName(date) + ', ' + date.getDate() + ' ' + getMonthName(date) + '</span>';
            html += '<button class="toggle-holiday ' + (isHoliday ? 'is-holiday' : '') + '" onclick="toggleHoliday(\'' + dateStr + '\')" title="' + (isHoliday ? 'Hapus libur' : 'Tandai libur') + '">';
            html += '<i class="fa-solid ' + (isHoliday ? 'fa-lock' : 'fa-unlock') + '"></i> ';
            html += isHoliday ? 'Libur' : 'Tandai Libur';
            html += '</button>';
            html += '</div></th>';
        }
        html += '</tr></thead>';

        html += '<tbody>';
        for (let r = 0; r < hours.length; r++) {
            const hour = hours[r];
            html += '<tr style="height: 70px; ' + (r < hours.length - 1 ? 'border-bottom: 1px solid #f3f4f6;' : '') + '">';
            html += '<td style="padding: 1rem; font-weight: 600; color: #9ca3af; background-color: #f9fafb;">' +
                String(hour).padStart(2, '0') + '.00</td>';

            for (let c = 0; c < weekDates.length; c++) {
                const dateStr = toDateStr(weekDates[c]);
                const isHoliday = holidayDates.indexOf(dateStr) !== -1;

                if (isHoliday && r === 0) {
                    html += '<td rowspan="' + hours.length + '" style="padding: 0; border-left: 1px solid #f3f4f6; vertical-align: middle; background-color: #fef2f2;">';
                    html += '<div class="locked-day" style="height: 100%; min-height: 200px;">';
                    html += '<i class="fa-solid fa-lock" style="font-size: 1.5rem; margin-bottom: 0.5rem; color: #dc2626;"></i>';
                    html += '<span style="font-size: 0.8rem; font-weight: 600; color: #dc2626;">Tutup</span>';
                    html += '<span style="font-size: 0.65rem; color: #9ca3af;">Tanggal Merah</span>';
                    html += '</div></td>';
                } else if (!isHoliday) {
                    const randomIdx = Math.floor(Math.random() * statusClasses.length);
                    html += '<td style="padding: 0.5rem; border-left: 1px solid #f3f4f6; vertical-align: top;">';
                    html += '<div class="slot-status ' + statusClasses[randomIdx] + '">';
                    html += '<div><strong>' + String(hour).padStart(2, '0') + '.00 - ' + String(hour + 1).padStart(2, '0') + '.00</strong><br>' + statusLabels[randomIdx] + '</div>';
                    html += '<i class="fa-solid fa-ellipsis"></i>';
                    html += '</div></td>';
                }
            }
            html += '</tr>';
        }
        html += '</tbody></table>';

        document.getElementById('table-container').innerHTML = html;
    }

    function toggleHoliday(dateStr) {
        const idx = holidayDates.indexOf(dateStr);
        if (idx === -1) {
            holidayDates.push(dateStr);
        } else {
            holidayDates.splice(idx, 1);
        }
        renderTable();
    }

    function applyFilters() {
        const dateVal = document.getElementById('filter-date').value;
        if (dateVal) currentDate = new Date(dateVal + 'T00:00:00');
        renderTable();
    }

    function prevWeek() {
        currentDate.setDate(currentDate.getDate() - 7);
        document.getElementById('filter-date').value = toDateStr(currentDate);
        renderTable();
    }

    function nextWeek() {
        currentDate.setDate(currentDate.getDate() + 7);
        document.getElementById('filter-date').value = toDateStr(currentDate);
        renderTable();
    }

    function resetFilter() {
        holidayDates = [];
        currentDate = new Date();
        document.getElementById('filter-date').value = toDateStr(currentDate);
        document.getElementById('filter-field').value = fields[0].id;
        document.getElementById('filter-sport').value = 'Semua Olahraga';
        renderTable();
    }

    document.addEventListener('DOMContentLoaded', function () {
        initFilters();
        renderTable();

        document.getElementById('filter-field').addEventListener('change', applyFilters);
        document.getElementById('filter-sport').addEventListener('change', applyFilters);
        document.getElementById('filter-date').addEventListener('change', applyFilters);
        document.getElementById('prev-week').addEventListener('click', prevWeek);
        document.getElementById('next-week').addEventListener('click', nextWeek);
        document.getElementById('reset-filter').addEventListener('click', resetFilter);
    });
</script>

</body>
</html>