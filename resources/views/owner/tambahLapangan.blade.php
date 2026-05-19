<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($field) ? 'Edit Lapangan' : 'Tambah Lapangan' }}</title>

    <!-- Panggil file CSS baru di sini -->
    @vite([
        'resources/css/owner-dashboard.css', 
        'resources/css/owner-bookings.css',
        'resources/css/owner-form-field.css'
    ])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
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
                <button class="notif-btn"><i class="fa-solid fa-bell"></i></button>
                <button class="notif-btn question"><i class="fa-solid fa-circle-question"></i></button>
                <div class="profile-box">
                    <div>
                        <h5>Namtan</h5>
                        <p>Owner Profile</p>
                    </div>
                    <img src="https://i.pravatar.cc/100" alt="Profile">
                </div>
            </div>
        </div>

        {{-- HEADER FORM --}}
        <div class="welcome-section">
            <div>
                <h1>{{ isset($field) ? 'Edit Data Lapangan' : 'Tambah Lapangan Baru' }}</h1>
                <p>Isi informasi detail mengenai lapangan olahraga di bawah ini.</p>
            </div>
            
            <a href="/owner/kelolaLapangan" class="add-btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- CONTAINER FORM --}}
        <div class="form-container">
            
            <form action="{{ isset($field) ? '/owner/fields/'.$field->id.'/update' : '/owner/fields/store' }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($field))
                    @method('PUT')
                @endif

                {{-- Baris 1: Nama & Jenis --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lapangan</label>
                        <input type="text" name="name" class="form-control" value="{{ isset($field) ? $field->name : '' }}" placeholder="Contoh: Lapangan A" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Olahraga</label>
                        <select name="type" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Futsal" {{ (isset($field) && $field->type == 'Futsal') ? 'selected' : '' }}>Futsal</option>
                            <option value="Basket" {{ (isset($field) && $field->type == 'Basket') ? 'selected' : '' }}>Basket</option>
                            <option value="Badminton" {{ (isset($field) && $field->type == 'Badminton') ? 'selected' : '' }}>Badminton</option>
                        </select>
                    </div>
                </div>

                {{-- Baris 2: Harga & Foto --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Harga Sewa (per Jam)</label>
                        <input type="number" name="price" class="form-control" value="{{ isset($field) ? $field->price : '' }}" placeholder="Contoh: 120000" required>
                    </div>

                    <div class="form-group">
                        <label>Foto Lapangan</label>
                        <input type="file" name="image" class="form-control">
                        @if(isset($field))
                            <small class="form-help">*Abaikan jika tidak ingin mengubah foto lama</small>
                        @endif
                    </div>
                </div>

                {{-- Baris 3: Jam Operasional --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Jam Buka</label>
                        <input type="time" name="open_time" class="form-control" value="{{ isset($field) ? $field->open_time : '08:00' }}" required>
                    </div>

                    <div class="form-group">
                        <label>Jam Tutup</label>
                        <input type="time" name="close_time" class="form-control" value="{{ isset($field) ? $field->close_time : '22:00' }}" required>
                    </div>
                </div>

                {{-- Fasilitas (Checkbox) --}}
                <div class="facilities-section">
                    <label class="section-label">Fasilitas Lapangan</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="facilities[]" value="wifi" {{ (isset($field) && in_array('wifi', $field->facilities)) ? 'checked' : '' }}> 
                            <i class="fa-solid fa-wifi"></i> Wi-Fi
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="facilities[]" value="shower" {{ (isset($field) && in_array('shower', $field->facilities)) ? 'checked' : '' }}> 
                            <i class="fa-solid fa-shower"></i> Kamar Mandi
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="facilities[]" value="parking" {{ (isset($field) && in_array('parking', $field->facilities)) ? 'checked' : '' }}> 
                            <i class="fa-solid fa-car"></i> Parkir Luas
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="facilities[]" value="fan" {{ (isset($field) && in_array('fan', $field->facilities)) ? 'checked' : '' }}> 
                            <i class="fa-solid fa-fan"></i> Kipas / AC
                        </label>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="form-actions">
                    <button type="reset" class="add-btn">Reset</button>
                    <button type="submit" class="add-btn">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </main>
</div>

</body>
</html>