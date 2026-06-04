<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeliharaan Kontrol</title>

    @vite(['resources/css/app.css', 'resources/css/pemeliharaanDanKontrol.css', 'resources/js/app.js'])
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

        <div class="welcome-section">
            <div>
                <h1>Pemeliharaan & Kontrol</h1>
                <p>Pantau dan kelola perawatan lapangan olahraga Anda.</p>
            </div>
            <a href="javascript:void(0)" class="add-btn" onclick="document.getElementById('createReportModal').style.display='flex'">
                <i class="fa-solid fa-plus"></i> Laporan Baru
            </a>
        </div>

         @php
            $totalTasks = $maintenances->count();
            $waitingTasks = $maintenances->where('status', 'Menunggu')->count();
            $inProgressTasks = $maintenances->where('status', 'Dikerjakan')->count();
            $completedTasks = $maintenances->where('status', 'Selesai')->count();
            $overdueTasks = $maintenances->filter(fn($m) => $m->schedule_date && $m->schedule_date->lt(now()->startOfDay()) && $m->status !== 'Selesai')->count();
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
          <p>Dikerjakan</p>
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

    <div class="maintenance-layout" x-data="{
        targetDeleteId: null,
        confirmDelete(id) {
            this.targetDeleteId = id;
            this.$dispatch('open-modal-delete-maintenance');
        },
        executeDelete() {
            if (this.targetDeleteId) {
                const csrf = document.querySelector('meta[name=\'csrf-token\']')?.content || '';
                fetch('/owner/maintenances/' + this.targetDeleteId, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrf },
                })
                .then(r => r.json())
                .then(() => {
                    const row = document.querySelector('#taskTable tr[data-id=\'' + this.targetDeleteId + '\']');
                    if (row) row.remove();
                    document.querySelector('.detail-panel')?.classList.remove('show');
                    showToast('Tugas berhasil dihapus');
                    location.reload();
                })
                .catch(() => showToast('Gagal menghapus tugas', true));
            }
        }
    }" x-on:modal-confirmed.window="if ($event.detail.name === 'delete-maintenance') executeDelete()">

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

          <button class="reset-btn">Atur Ulang Filter</button>

        </div>

        <!-- TABLE -->
        <div class="table-container">

          <table>

            <thead>
              <tr>
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
                $isOverdue = $m->schedule_date && $m->schedule_date->lt(now()->startOfDay()) && $m->status !== 'Selesai';
              @endphp
              <tr data-id="{{ $m->id }}"
                  data-tugas="{{ $m->task_name }}"
                  data-lapangan="{{ $m->field?->name ?? '-' }}"
                  data-field-id="{{ $m->field_id ?? '' }}"
                  data-jenis="{{ $m->type ?? '-' }}"
                  data-jadwal="{{ $m->schedule_date?->format('d M Y') ?? '-' }}"
                  data-prioritas="{{ $m->priority }}"
                  data-pj="{{ $m->pic_name ?? '-' }}"
                  data-status="{{ $m->status }}"
                  data-overdue="{{ $isOverdue ? 'true' : 'false' }}">
                <td data-label="tugas">{{ $m->task_name }}</td>
                <td data-label="lapangan">{{ $m->field?->name ?? '-' }}</td>
                <td data-label="jenis">{{ $m->type ?? '-' }}</td>
                <td data-label="jadwal">{{ $m->schedule_date?->format('d M Y') ?? '-' }}</td>
                <td data-label="prioritas"><span class="badge {{ $pClass }}">{{ $m->priority }}</span></td>
                <td data-label="pj">{{ $m->pic_name ?? '-' }}</td>
                <td data-label="status">
                    <span class="badge {{ $sClass }}">{{ $m->status }}</span>
                    @if($isOverdue)
                    <span class="badge overdue">Terlambat</span>
                    @endif
                </td>
                <td>
                    <button class="action-btn">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8">
                    <div style="text-align:center;padding:48px 24px;">
                        <div style="font-size:56px;margin-bottom:16px;">🛠️</div>
                        <div style="font-size:18px;font-weight:600;color:#374151;margin-bottom:8px;">Belum ada tugas pemeliharaan</div>
                        <div style="font-size:13px;color:#9ca3af;line-height:1.6;">
                            Klik tombol <strong style="color:#e60023;">"Laporan Baru"</strong><br>
                            untuk membuat laporan pemeliharaan pertama.
                        </div>
                    </div>
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

          <div class="detail-status-row">
            <div class="status-badge waiting">Menunggu</div>
            <div class="status-badge overdue" id="detail-overdue-badge" style="display:none;">Terlambat</div>
          </div>

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
              Ubah
            </button>

            <button class="save-btn" style="display:none;">
              <i class="fa-solid fa-check"></i>
              Simpan
            </button>

            <button class="cancel-btn" style="display:none;">
              <i class="fa-solid fa-xmark"></i>
              Batal
            </button>

            <button class="delete-btn" style="background: none; border: none; color: #dc2626; cursor: pointer; font-size: 13px; font-weight: 600; padding: 8px 12px; border-radius: 8px; transition: all .2s; display: inline-flex; align-items: center; gap: 6px; margin-left: auto;"
              onmouseover="this.style.background='#fee2e2'"
              onmouseout="this.style.background='none'"
              onclick="confirmDeleteFromDetail()">
              <i class="fa-solid fa-trash"></i> Hapus
            </button>
          </div>

        </div>

      </div>

      <x-custom-modal name="delete-maintenance"
                       type="confirm"
                       title="Hapus Tugas"
                       message="Yakin ingin menghapus tugas ini? Tindakan ini tidak dapat dibatalkan."
                       confirmText="Ya, Hapus"
                       cancelText="Kembali"
                       confirmVariant="danger" />

    </div>
    </main>

</div>

{{-- CREATE REPORT MODAL --}}
<div id="createReportModal" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.5);justify-content:center;align-items:center;" onclick="if(event.target===this)this.style.display='none'">
    <div style="background:white;border-radius:20px;padding:28px 24px;max-width:520px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.25);max-height:90vh;overflow-y:auto;">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">Laporan Pemeliharaan Baru</h3>
            <span onclick="document.getElementById('createReportModal').style.display='none'" style="cursor:pointer;font-size:24px;color:#999;line-height:1;">&times;</span>
        </div>
        <form id="createReportForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lapangan</label>
                    <select name="field_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400" style="padding-right:36px;appearance:none;-webkit-appearance:none;-moz-appearance:none;background:#fff url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E\") no-repeat right 14px center;">
                        <option value="">Pilih Lapangan</option>
                        @foreach($fields as $f)
                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Tugas</label>
                    <input type="text" name="task_name" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400" placeholder="Contoh: Perbaikan Lampu Lapangan A">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis</label>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400" style="padding-right:36px;appearance:none;-webkit-appearance:none;-moz-appearance:none;background:#fff url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E\") no-repeat right 14px center;">
                            <option value="Elektrikal">Elektrikal</option>
                            <option value="Lapangan">Lapangan</option>
                            <option value="Kebersihan">Kebersihan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jadwal</label>
                        <input type="date" name="schedule_date" required value="{{ now()->format('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Prioritas</label>
                        <select name="priority" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400" style="padding-right:36px;appearance:none;-webkit-appearance:none;-moz-appearance:none;background:#fff url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E\") no-repeat right 14px center;">
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang" selected>Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Penanggung Jawab</label>
                        <input type="text" name="pic_name" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400" placeholder="Nama teknisi">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400" placeholder="Deskripsi tambahan..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" class="flex-1 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors" onclick="document.getElementById('createReportModal').style.display='none'">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-red-600 text-white rounded-xl font-semibold text-sm hover:bg-red-700 transition-colors" id="createReportSubmit"><i class="fa-solid fa-floppy-disk mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
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
            const isOverdue = row.dataset.overdue === 'true';

            const badge = detailPanel.querySelector('.status-badge');
            badge.textContent = status;
            badge.className = 'status-badge ' + statusData.badgeClass;

            const overdueBadge = document.getElementById('detail-overdue-badge');
            overdueBadge.style.display = isOverdue ? 'inline-flex' : 'none';

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
            saveBtn.disabled = false;
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

            if (!id) return;

            const oldValues = {};
            detailPanel.querySelectorAll('[data-field]').forEach(el => {
                oldValues[el.dataset.field] = el.textContent.trim();
            });
            const oldStatus = detailPanel.querySelector('.status-badge').textContent.trim();
            const oldOverdue = currentEditRow?.dataset.overdue === 'true';

            const data = {};
            const fieldMap = {
                tugas: 'task_name',
                jenis: 'type',
                jadwal: 'schedule_date',
                prioritas: 'priority',
            };

            detailPanel.querySelectorAll('[data-field]').forEach(el => {
                const input = el.querySelector('.edit-input, .edit-select');
                if (input) {
                    const val = input.value.trim();
                    const backendKey = fieldMap[el.dataset.field] || el.dataset.field;
                    data[backendKey] = val;
                }
            });

            const badge = detailPanel.querySelector('.status-badge');
            const sel = badge.querySelector('.edit-select');
            if (sel) {
                newStatus = sel.value.trim();
                data.status = newStatus;
            }

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            fetch('/owner/maintenances/' + id + '/update', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify(data),
            })
            .then(r => {
                if (!r.ok) throw new Error('Gagal');
                return r.json();
            })
            .then(res => {
                detailPanel.querySelectorAll('[data-field]').forEach(el => {
                    const input = el.querySelector('.edit-input, .edit-select');
                    if (input) {
                        el.textContent = input.value.trim();
                    }
                });

                if (newStatus) {
                    badge.textContent = newStatus;
                    badge.className = 'status-badge ' + (statusMap[newStatus]?.badgeClass || '');
                    updateHistory(newStatus);

                    const badgeCell = currentEditRow?.querySelectorAll('td')[6];
                    const statusBadge = badgeCell?.querySelector('.badge');
                    if (statusBadge) {
                        statusBadge.textContent = newStatus;
                        statusBadge.className = 'badge ' + (
                            newStatus === 'Menunggu' ? 'waiting' :
                            newStatus === 'Dikerjakan' ? 'progress' :
                            newStatus === 'Selesai' ? 'done' : ''
                        );

                        const updated = res.maintenance;
                        const today = new Date(); today.setHours(0, 0, 0, 0);
                        const sched = updated.schedule_date ? new Date(updated.schedule_date) : null;
                        if (sched) sched.setHours(0, 0, 0, 0);
                        const isOverdue = sched && sched < today && updated.status !== 'Selesai';
                        currentEditRow.dataset.overdue = isOverdue ? 'true' : 'false';

                        const overdueSpan = badgeCell.querySelector('.badge.overdue');
                        if (isOverdue && !overdueSpan) {
                            const span = document.createElement('span');
                            span.className = 'badge overdue';
                            span.textContent = 'Terlambat';
                            badgeCell.appendChild(span);
                        } else if (!isOverdue && overdueSpan) {
                            overdueSpan.remove();
                        }

                        const detailOverdue = document.getElementById('detail-overdue-badge');
                        detailOverdue.style.display = isOverdue ? 'inline-flex' : 'none';
                    }
                }

                exitEditMode();
                showToast('Tugas berhasil diperbarui');
            })
            .catch(e => {
                console.error('Save error:', e);
                detailPanel.querySelectorAll('[data-field]').forEach(el => {
                    el.textContent = oldValues[el.dataset.field] || '';
                });
                badge.textContent = oldStatus;
                badge.className = 'status-badge ' + (statusMap[oldStatus]?.badgeClass || '');
                updateHistory(oldStatus);

                const detailOverdue = document.getElementById('detail-overdue-badge');
                detailOverdue.style.display = oldOverdue ? 'inline-flex' : 'none';

                saveBtn.disabled = false;
                exitEditMode();
                showToast('Gagal memperbarui tugas', true);
            });
        }

        function confirmDeleteFromDetail() {
            const id = currentEditRow?.dataset.id;
            if (id) {
                const alpineEl = document.querySelector('[x-data]');
                if (alpineEl && window.Alpine) {
                    window.Alpine.$data(alpineEl).confirmDelete(id);
                }
            }
        }

        function showToast(msg, isError) {
            const existing = document.querySelector('.custom-toast');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.className = 'custom-toast';
            toast.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:999999;padding:14px 20px;border-radius:12px;font-weight:600;font-size:14px;color:white;box-shadow:0 8px 32px rgba(0,0,0,.15);transition:all .3s ease;display:flex;align-items:center;gap:10px;max-width:400px;'
                + (isError ? 'background:#dc2626;' : 'background:#16a34a;');
            toast.innerHTML = (isError ? '❌ ' : '✅ ') + msg
                + '<span style="margin-left:12px;cursor:pointer;font-size:18px;opacity:.7;hover:opacity:1;" onclick="this.parentElement.remove()">&times;</span>';
            document.body.appendChild(toast);

            setTimeout(() => { const t = document.querySelector('.custom-toast'); if (t) t.remove(); }, 4000);
        }

        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return;

            row.addEventListener('click', (e) => {
                if (e.target.closest('.action-btn')) return;

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
                const matchField = !fVal || row.dataset.fieldId === fVal;
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

        /* === CREATE REPORT === */
        const createForm = document.getElementById('createReportForm');
        const createSubmit = document.getElementById('createReportSubmit');
        createForm?.addEventListener('submit', function (e) {
            e.preventDefault();
            createSubmit.disabled = true;
            createSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

            const formData = new FormData(createForm);
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

            fetch('/owner/maintenances/store', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf },
                body: formData,
            })
            .then(r => {
                if (!r.ok) throw new Error('Gagal');
                return r.json();
            })
            .then(() => {
                document.getElementById('createReportModal').style.display = 'none';
                createForm.reset();
                showToast('Tugas pemeliharaan berhasil dibuat');
                location.reload();
            })
            .catch(() => {
                createSubmit.disabled = false;
                createSubmit.innerHTML = '<i class="fa-solid fa-floppy-disk mr-1"></i> Simpan';
                showToast('Gagal membuat tugas', true);
            });
        });

    });

</script>

@include('owner.faq-popup')
</body>
</html>