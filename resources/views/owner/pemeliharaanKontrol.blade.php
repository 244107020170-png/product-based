<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeliharaan Kontrol</title>

    @vite(['resources/css/app.css', 'resources/css/pemeliharaanDanKontrol.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

                <button class="notif-btn" onclick="toggleFaqPopup()">
                    <i class="fa-solid fa-headset"></i>
                </button>
                <button class="notif-btn question">
                    <i class="fa-solid fa-circle-question"></i>
                </button>

                <div class="profile-box">
                    <div>
                        <h5>{{ auth()->user()->name }}</h5>
                        <p>Profil Pemilik</p>
                    </div>

                    <img src="https://i.pravatar.cc/100" alt="Profile">
                </div>
            </div>
        </div>

        <div class="welcome-section">
            <div>
                <h1>Pemeliharaan & Kontrol</h1>
                <p>Pantau dan kelola perawatan lapangan olahraga Anda.</p>
            </div>
            <a href="javascript:void(0)" class="add-btn">
                <i class="fa-solid fa-plus"></i> Laporan Baru
            </a>
        </div>

         @php
            $totalTasks = $maintenances->count();
            $waitingTasks = $maintenances->where('status', 'Menunggu')->count();
            $inProgressTasks = $maintenances->where('status', 'Dikerjakan')->count();
            $completedTasks = $maintenances->where('status', 'Selesai')->count();
            $overdueTasks = $maintenances->where('status', 'Menunggu')->where('schedule_date', '<', now()->toDateString())->count();
        @endphp
         <div class="cards">

      <div class="card">
        <div class="icon red">
          <i class="fa-solid fa-screwdriver-wrench"></i>
        </div>

        <div>
          <h2>{{ $totalTasks }}</h2>
          <p>Total Tugas</p>
        </div>
      </div>

      <div class="card">
        <div class="icon yellow">
          <i class="fa-regular fa-clock"></i>
        </div>

        <div>
          <h2>{{ $waitingTasks }}</h2>
          <p>Menunggu</p>
        </div>
      </div>

      <div class="card">
        <div class="icon blue">
          <i class="fa-solid fa-gears"></i>
        </div>

        <div>
          <h2>{{ $inProgressTasks }}</h2>
          <p>Sedang Dikerjakan</p>
        </div>
      </div>

      <div class="card">
        <div class="icon green">
          <i class="fa-solid fa-circle-check"></i>
        </div>

        <div>
          <h2>{{ $completedTasks }}</h2>
          <p>Selesai</p>
        </div>
      </div>

      <div class="card">
        <div class="icon pink">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <div>
          <h2>{{ $overdueTasks }}</h2>
          <p>Terlambat</p>
        </div>
      </div>

    </div>

    <div class="maintenance-layout">

      <div class="content-main">

        <!-- FILTER -->
        <div class="filter-box">

          <input type="text" placeholder="Cari tugas, lapangan, teknisi...">

          <select id="filter-field">
            <option value="">Semua Lapangan</option>
            @foreach ($fields as $f)
            <option value="{{ $f->id }}">{{ $f->name }}</option>
            @endforeach
          </select>

          <select id="filter-type">
            <option value="">Semua Jenis</option>
            <option value="Elektrikal">Elektrikal</option>
            <option value="Lapangan">Lapangan</option>
            <option value="Kebersihan">Kebersihan</option>
            <option value="Lainnya">Lainnya</option>
          </select>

          <select id="filter-status">
            <option value="">Semua Status</option>
            <option value="Menunggu">Menunggu</option>
            <option value="Dikerjakan">Dikerjakan</option>
            <option value="Selesai">Selesai</option>
          </select>

          <button class="reset-btn">Reset Filter</button>

        </div>

        <!-- TABLE -->
        <div class="table-container">

          <table>

            <thead>
              <tr>
                <td>
                    <input type="checkbox">
                </td>
                <th>Tugas</th>
                <th>Lapangan</th>
                <th>Jenis</th>
                <th>Jadwal</th>
                <th>Prioritas</th>
                <th>Penanggung Jawab</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody id="taskTable">

              @forelse ($maintenances as $m)
              @php
                $pClass = match($m->priority) {
                    'Tinggi' => 'high',
                    'Sedang' => 'medium',
                    'Rendah' => 'low',
                    default => 'medium',
                };
                $sClass = match($m->status) {
                    'Menunggu' => 'waiting',
                    'Dikerjakan' => 'progress',
                    'Selesai' => 'done',
                    default => 'waiting',
                };
              @endphp
              <tr data-id="{{ $m->id }}"
                  data-tugas="{{ $m->task_name }}"
                  data-lapangan="{{ $m->field?->name ?? '-' }}"
                  data-jenis="{{ $m->type ?? '-' }}"
                  data-jadwal="{{ $m->schedule_date?->format('d M Y') ?? '-' }}"
                  data-prioritas="{{ $m->priority }}"
                  data-pj="{{ $m->pic_name ?? '-' }}"
                  data-status="{{ $m->status }}">
                <td>
                    <input type="checkbox">
                </td>
                <td data-label="tugas">{{ $m->task_name }}</td>
                <td data-label="lapangan">{{ $m->field?->name ?? '-' }}</td>
                <td data-label="jenis">{{ $m->type ?? '-' }}</td>
                <td data-label="jadwal">{{ $m->schedule_date?->format('d M Y') ?? '-' }}</td>
                <td data-label="prioritas"><span class="badge {{ $pClass }}">{{ $m->priority }}</span></td>
                <td data-label="pj">{{ $m->pic_name ?? '-' }}</td>
                <td data-label="status"><span class="badge {{ $sClass }}">{{ $m->status }}</span></td>
                <td>
                    <button class="action-btn">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="9" style="text-align: center; padding: 40px; color: #888;">
                    <i class="fa-solid fa-inbox" style="font-size: 48px; display: block; margin-bottom: 16px; color: #ccc;"></i>
                    Belum ada tugas pemeliharaan
                </td>
              </tr>
              @endforelse

            </tbody>

          </table>

        </div>

      </div>

      {{-- DETAIL PANEL --}}
      <div class="detail-panel">

        <div class="detail-card">

          <div class="detail-header">

            <h3>Detail Pemeliharaan</h3>

            <button class="detail-close">
                <i class="fa-solid fa-xmark"></i>
            </button>

          </div>

          <div class="status-badge waiting">Menunggu</div>

          <div class="task-id">Tugas #MT-001</div>

          {{-- TUGAS INFO --}}
          <div class="detail-section">

            <h4>Informasi Tugas</h4>

            <div class="detail-info">

              <div>
                <span>Tugas</span>
                <strong data-field="tugas">Perbaikan Lampu Lapangan A</strong>
              </div>

              <div>
                <span>Lapangan</span>
                <strong data-field="lapangan">Lapangan A</strong>
              </div>

              <div>
                <span>Jenis</span>
                <strong data-field="jenis">Elektrikal</strong>
              </div>

              <div>
                <span>Jadwal</span>
                <strong data-field="jadwal">20 Mei 2025</strong>
              </div>

              <div>
                <span>Prioritas</span>
                <strong data-field="prioritas">Tinggi</strong>
              </div>

            </div>

          </div>

          {{-- PENANGGUNG JAWAB --}}
          <div class="detail-section">

            <h4>Penanggung Jawab</h4>

            <div class="detail-profile">

              <img src="https://i.pravatar.cc/100" alt="">

              <div>
                <h4 class="pj-name">Budi Setiawan</h4>
                <p>Teknisi Lapangan</p>
                <p>budi@arenasport.com</p>
              </div>

            </div>

          </div>

          {{-- HISTORY --}}
          <div class="detail-section">

            <h4>Riwayat Tugas</h4>

            <ul class="history-list">

              <li style="color: black">Tugas dibuat</li>
              <li style="color: #F29E10">Menunggu dikerjakan</li>

            </ul>

          </div>

          {{-- BUTTONS --}}
          <div class="edit-actions">
            <button class="edit-btn">
              <i class="fa-solid fa-pen"></i>
              Edit
            </button>

            <button class="save-btn" style="display:none;">
              <i class="fa-solid fa-check"></i>
              Simpan
            </button>

            <button class="cancel-btn" style="display:none;">
              <i class="fa-solid fa-xmark"></i>
              Batal
            </button>
          </div>

        </div>

      </div>

    </div>
    </main>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const detailPanel = document.querySelector('.detail-panel');
        const closeBtn = detailPanel?.querySelector('.detail-close');
        const rows = document.querySelectorAll('#taskTable tr');

        const statusMap = {
            'Menunggu': { badgeClass: 'waiting', history: [
                { label: 'Tugas dibuat', color: 'black' },
                { label: 'Menunggu dikerjakan', color: '#F29E10' },
            ]},
            'Dikerjakan': { badgeClass: 'progress', history: [
                { label: 'Tugas dibuat', color: 'black' },
                { label: 'Menunggu dikerjakan', color: 'black' },
                { label: 'Sedang dikerjakan', color: '#2563eb' },
            ]},
            'Selesai': { badgeClass: 'done', history: [
                { label: 'Tugas dibuat', color: 'black' },
                { label: 'Menunggu dikerjakan', color: 'black' },
                { label: 'Sedang dikerjakan', color: 'black' },
                { label: 'Selesai', color: '#16a34a' },
            ]},
        };

        let currentEditRow = null;

        const editBtn = detailPanel?.querySelector('.edit-btn');
        const saveBtn = detailPanel?.querySelector('.save-btn');
        const cancelBtn = detailPanel?.querySelector('.cancel-btn');

        function updateHistory(status) {
            const list = detailPanel.querySelector('.history-list');
            list.innerHTML = '';
            const steps = statusMap[status]?.history || [];
            steps.forEach(s => {
                const li = document.createElement('li');
                li.textContent = s.label;
                li.style.color = s.color;
                list.appendChild(li);
            });
        }

        function populateDetail(row) {
            currentEditRow = row;

            const tugas = row.dataset.tugas || '';
            const lapangan = row.dataset.lapangan || '';
            const jenis = row.dataset.jenis || '';
            const jadwal = row.dataset.jadwal || '';
            const prioritas = row.dataset.prioritas || '';
            const pj = row.dataset.pj || '';
            const status = row.dataset.status || 'Menunggu';
            const statusData = statusMap[status] || statusMap['Menunggu'];

            const badge = detailPanel.querySelector('.status-badge');
            badge.textContent = status;
            badge.className = 'status-badge ' + statusData.badgeClass;

            const taskId = row.dataset.id || '0';
            detailPanel.querySelector('.task-id').textContent = 'Tugas #MT-' + String(taskId).padStart(3, '0');
            detailPanel.querySelector('[data-field="tugas"]').textContent = tugas;
            detailPanel.querySelector('[data-field="lapangan"]').textContent = lapangan;
            detailPanel.querySelector('[data-field="jenis"]').textContent = jenis;
            detailPanel.querySelector('[data-field="jadwal"]').textContent = jadwal;
            detailPanel.querySelector('[data-field="prioritas"]').textContent = prioritas;
            detailPanel.querySelector('.pj-name').textContent = pj;

            updateHistory(status);
        }

        function enterEditMode() {
            editBtn.style.display = 'none';
            saveBtn.style.display = 'flex';
            cancelBtn.style.display = 'flex';

            detailPanel.querySelectorAll('[data-field]').forEach(el => {
                const field = el.dataset.field;
                const val = el.textContent.trim();

                if (field === 'prioritas') {
                    const select = document.createElement('select');
                    select.className = 'edit-input edit-select';
                    ['Tinggi', 'Sedang', 'Rendah'].forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s;
                        opt.textContent = s;
                        if (s === val) opt.selected = true;
                        select.appendChild(opt);
                    });
                    el.textContent = '';
                    el.appendChild(select);
                } else {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'edit-input';
                    input.value = val;
                    el.textContent = '';
                    el.appendChild(input);
                }
            });

            const currentStatus = detailPanel.querySelector('.status-badge').textContent.trim();
            const select = document.createElement('select');
            select.className = 'edit-input edit-select';
            ['Menunggu', 'Dikerjakan', 'Selesai'].forEach(s => {
                const opt = document.createElement('option');
                opt.value = s;
                opt.textContent = s;
                if (s === currentStatus) opt.selected = true;
                select.appendChild(opt);
            });
            const badge = detailPanel.querySelector('.status-badge');
            badge.textContent = '';
            badge.appendChild(select);
        }

        function exitEditMode() {
            editBtn.style.display = 'flex';
            saveBtn.style.display = 'none';
            cancelBtn.style.display = 'none';

            detailPanel.querySelectorAll('[data-field]').forEach(el => {
                const input = el.querySelector('.edit-input, .edit-select');
                if (input) {
                    el.textContent = input.value.trim();
                }
            });

            const badge = detailPanel.querySelector('.status-badge');
            const sel = badge.querySelector('.edit-select');
            if (sel) {
                const val = sel.value.trim();
                badge.textContent = val;
                badge.className = 'status-badge ' + (statusMap[val]?.badgeClass || '');
                updateHistory(val);
            }
        }

        function saveEdit() {
            let newStatus = null;
            const id = currentEditRow?.dataset.id;
            const data = {};

            detailPanel.querySelectorAll('[data-field]').forEach(el => {
                const input = el.querySelector('.edit-input, .edit-select');
                if (input) {
                    const val = input.value.trim();
                    el.textContent = val;
                    data[el.dataset.field] = val;
                }
            });

            const badge = detailPanel.querySelector('.status-badge');
            const sel = badge.querySelector('.edit-select');
            if (sel) {
                newStatus = sel.value.trim();
                data.status = newStatus;
                badge.textContent = newStatus;
                badge.className = 'status-badge ' + (statusMap[newStatus]?.badgeClass || '');
                updateHistory(newStatus);
            }

            editBtn.style.display = 'flex';
            saveBtn.style.display = 'none';
            cancelBtn.style.display = 'none';

            if (newStatus && currentEditRow) {
                const badgeCell = currentEditRow.querySelectorAll('td')[7];
                const b = badgeCell?.querySelector('.badge');
                if (b) {
                    b.textContent = newStatus;
                    b.className = 'badge ' + (
                        newStatus === 'Menunggu' ? 'waiting' :
                        newStatus === 'Dikerjakan' ? 'progress' :
                        newStatus === 'Selesai' ? 'done' : ''
                    );
                }
            }

            if (id) {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                fetch('/owner/maintenances/' + id + '/update', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify(data),
                }).catch(err => console.error('Gagal update:', err));
            }
        }

        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return;

            row.addEventListener('click', (e) => {
                if (e.target.closest('.action-btn') || e.target.closest('input[type="checkbox"]')) return;

                rows.forEach(r => {
                    if (!r.querySelector('td[colspan]')) r.style.background = 'white';
                });
                row.style.background = '#fff5f5';
            });

            const actionBtn = row.querySelector('.action-btn');
            if (actionBtn) {
                actionBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    exitEditMode();
                    populateDetail(row);
                    detailPanel?.classList.toggle('show');
                });
            }
        });

        closeBtn?.addEventListener('click', () => {
            detailPanel?.classList.remove('show');
            exitEditMode();
        });

        editBtn?.addEventListener('click', enterEditMode);
        saveBtn?.addEventListener('click', saveEdit);
        cancelBtn?.addEventListener('click', exitEditMode);

        /* === FILTER === */

        const fieldFilter = document.getElementById('filter-field');
        const typeFilter = document.getElementById('filter-type');
        const statusFilter = document.getElementById('filter-status');
        const resetFilterBtn = document.querySelector('.reset-btn');
        const searchInput = document.querySelector('.filter-box input[type="text"]');

        function applyFilters() {
            const fVal = fieldFilter?.value || '';
            const tVal = typeFilter?.value || '';
            const sVal = statusFilter?.value || '';
            const q = (searchInput?.value || '').toLowerCase();

            rows.forEach(row => {
                if (row.querySelector('td[colspan]')) return;
                const matchField = !fVal || row.dataset.lapangan === document.querySelector('#filter-field option[value="' + fVal + '"]')?.textContent;
                const matchType = !tVal || row.dataset.jenis === tVal;
                const matchStatus = !sVal || row.dataset.status === sVal;
                const matchSearch = !q || row.textContent.toLowerCase().includes(q);
                row.style.display = (matchField && matchType && matchStatus && matchSearch) ? '' : 'none';
            });
        }

        fieldFilter?.addEventListener('change', applyFilters);
        typeFilter?.addEventListener('change', applyFilters);
        statusFilter?.addEventListener('change', applyFilters);
        searchInput?.addEventListener('input', applyFilters);

        resetFilterBtn?.addEventListener('click', function () {
            if (fieldFilter) fieldFilter.value = '';
            if (typeFilter) typeFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            if (searchInput) searchInput.value = '';
            rows.forEach(row => {
                if (!row.querySelector('td[colspan]')) row.style.display = '';
            });
        });

    });

</script>

@include('owner.faq-popup')
</body>
</html>