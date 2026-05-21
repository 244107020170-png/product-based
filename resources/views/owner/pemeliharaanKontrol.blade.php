<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeliharaan Kontrol</title>

    @vite(['resources/css/pemeliharaanDanKontrol.css'])

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

         <div class="cards">

      <div class="card">
        <div class="icon red">
          <i class="fa-solid fa-screwdriver-wrench"></i>
        </div>

        <div>
          <h2>24</h2>
          <p>Total Tugas</p>
        </div>
      </div>

      <div class="card">
        <div class="icon yellow">
          <i class="fa-regular fa-clock"></i>
        </div>

        <div>
          <h2>7</h2>
          <p>Menunggu</p>
        </div>
      </div>

      <div class="card">
        <div class="icon blue">
          <i class="fa-solid fa-gears"></i>
        </div>

        <div>
          <h2>6</h2>
          <p>Sedang Dikerjakan</p>
        </div>
      </div>

      <div class="card">
        <div class="icon green">
          <i class="fa-solid fa-circle-check"></i>
        </div>

        <div>
          <h2>11</h2>
          <p>Selesai</p>
        </div>
      </div>

      <div class="card">
        <div class="icon pink">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <div>
          <h2>3</h2>
          <p>Overdue</p>
        </div>
      </div>

    </div>

    <!-- FILTER -->
    <div class="filter-box">

      <input type="text" placeholder="Cari tugas, lapangan, teknisi...">

      <select>
        <option>Semua Lapangan</option>
      </select>

      <select>
        <option>Semua Jenis</option>
      </select>

      <select>
        <option>Semua Status</option>
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

          <tr>
            <td>
                <input type="checkbox">
            </td>
            <td>Perbaikan Lampu Lapangan A</td>
            <td>Lapangan A</td>
            <td>Elektrikal</td>
            <td>20 Mei 2025</td>
            <td><span class="badge high">Tinggi</span></td>
            <td>Budi Setiawan</td>
            <td><span class="badge waiting">Menunggu</span></td>
            <td>
                <button class="action-btn">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
            </td>
          </tr>

          <tr>
            <td>
                <input type="checkbox">
            </td>
            <td>Pengecekan Rumput Sintetis</td>
            <td>Lapangan B</td>
            <td>Lapangan</td>
            <td>21 Mei 2025</td>
            <td><span class="badge medium">Sedang</span></td>
            <td>Andi Permana</td>
            <td><span class="badge progress">Dikerjakan</span></td>
            <td>
                <button class="action-btn">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
            </td>
          </tr>

          <tr>
            <td>
                <input type="checkbox">
            </td>
            <td>Kalibrasi Scoreboard</td>
            <td>Lapangan C</td>
            <td>Elektrikal</td>
            <td>23 Mei 2025</td>
            <td><span class="badge low">Rendah</span></td>
            <td>Rizky</td>
            <td><span class="badge done">Selesai</span></td>
            <td>
                <button class="action-btn">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
            </td>
          </tr>

        </tbody>

      </table>

    </div>
    </main>

</div>
<script>
    const rows = document.querySelectorAll("#taskTable tr");

    rows.forEach((row) => {
      row.addEventListener("click", () => {

        rows.forEach((r) => {
          r.style.background = "white";
        });

        row.style.background = "#fff5f5";

        alert("Detail tugas dibuka!");
      });
    });

    const addButton = document.querySelector(".add-btn");

    addButton.addEventListener("click", () => {
      alert("Tambah Pemeliharaan clicked!");
    });
</script>

</body>
</html>