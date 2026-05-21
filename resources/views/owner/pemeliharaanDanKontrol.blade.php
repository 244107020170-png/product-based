<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pemeliharaan Kontrol</title>

  <link rel="stylesheet" href="pemeliharaanDanKontrol.css"/>

  <!-- ICON -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
  />
</head>

<body>

  <!-- SIDEBAR -->
  <div class="sidebar">

    <div class="logo">
      <h2>SPIES SPORT</h2>
      <p>Owner Panel</p>
    </div>

    <ul class="menu">
      <li><i class="fa-solid fa-table-columns"></i> Dashboard</li>
      <li><i class="fa-solid fa-futbol"></i> Kelola Lapangan</li>
      <li><i class="fa-regular fa-calendar"></i> Jadwal dan Slot</li>
      <li><i class="fa-solid fa-ticket"></i> Pengelolaan Booking</li>
      <li><i class="fa-solid fa-tags"></i> Promosi dan Diskon</li>

      <li class="active">
        <i class="fa-solid fa-screwdriver-wrench"></i>
        Pemeliharaan Kontrol
      </li>
    </ul>

    <div class="bottom-menu">
      <li><i class="fa-solid fa-gear"></i> Pengaturan</li>
      <li><i class="fa-solid fa-right-from-bracket"></i> Log Out</li>
    </div>

  </div>

  <!-- MAIN -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">

      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search bookings, customers, lapangan...">
      </div>

      <div class="top-right">
        <i class="fa-solid fa-bell"></i>

        <div class="profile">
          <img src="https://i.pravatar.cc/40" alt="">
          <div>
            <h4>Namtan</h4>
            <p>Owner Profile</p>
          </div>
        </div>
      </div>

    </div>

    <!-- TITLE -->
    <div class="header">
      <div>
        <h1>Pemeliharaan Kontrol</h1>
        <p>Pantau dan kelola semua aktivitas pemeliharaan fasilitas lapangan.</p>
      </div>

      <button class="add-btn">
        <i class="fa-solid fa-plus"></i>
        Tambah Pemeliharaan
      </button>
    </div>

    <!-- CARDS -->
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
            <th>Tugas</th>
            <th>Lapangan</th>
            <th>Jenis</th>
            <th>Jadwal</th>
            <th>Prioritas</th>
            <th>Penanggung Jawab</th>
            <th>Status</th>
          </tr>
        </thead>

        <tbody id="taskTable">

          <tr>
            <td>Perbaikan Lampu Lapangan A</td>
            <td>Lapangan A</td>
            <td>Elektrikal</td>
            <td>20 Mei 2025</td>
            <td><span class="badge high">Tinggi</span></td>
            <td>Budi Setiawan</td>
            <td><span class="badge waiting">Menunggu</span></td>
          </tr>

          <tr>
            <td>Pengecekan Rumput Sintetis</td>
            <td>Lapangan B</td>
            <td>Lapangan</td>
            <td>21 Mei 2025</td>
            <td><span class="badge medium">Sedang</span></td>
            <td>Andi Permana</td>
            <td><span class="badge progress">Dikerjakan</span></td>
          </tr>

          <tr>
            <td>Kalibrasi Scoreboard</td>
            <td>Lapangan C</td>
            <td>Elektrikal</td>
            <td>23 Mei 2025</td>
            <td><span class="badge low">Rendah</span></td>
            <td>Rizky</td>
            <td><span class="badge done">Selesai</span></td>
          </tr>

        </tbody>

      </table>

    </div>

  </div>

  <script src="script.js"></script>
</body>
</html>