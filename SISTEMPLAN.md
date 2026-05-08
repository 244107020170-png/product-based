# PERANCANGAN SISTEM

## Arsitektur Teknologi (Tech Stack)
Dalam pengembangan sistem **Spies Sport – Platform Booking Lapangan dan Public Match**, digunakan teknologi yang dipilih berdasarkan pertimbangan kemudahan implementasi, efisiensi waktu pengembangan, serta kesesuaian dengan kebutuhan sistem berbasis web.

### Backend
- Framework: **Laravel (PHP, MVC)**
- Admin Panel: **Filament**
- Fitur bawaan: Routing, Authentication, ORM (Eloquent)

**Keunggulan:**
- Struktur kode lebih rapi dan terorganisir  
- Mempercepat pengembangan backend  
- Mendukung pembuatan admin panel secara instan  

### Frontend
- **HTML, CSS, JavaScript**
- **TailwindCSS**
- **Blade Template (Laravel)**

**Keunggulan:**
- Mudah diimplementasikan  
- Responsif dan cepat dikembangkan  
- Integrasi langsung dengan Laravel  

### Database
- **MySQL**

**Alasan pemilihan:**
- Mudah instalasi & konfigurasi  
- Terintegrasi default dengan Laravel  
- Mendukung sistem relasional (user, booking, match)  

### Tools Pendukung
- **Laragon** (local development environment)  
- **GitHub** (version control & kolaborasi tim)  

---

## Arsitektur Sistem
Sistem dibangun dengan arsitektur **Client-Server berbasis web** menggunakan pendekatan **MVC**.

**Komponen:**
- **Client (Frontend):** digunakan oleh player, owner, admin via browser  
- **Server (Backend Laravel):** mengelola logika bisnis, autentikasi, komunikasi database  
- **Database (MySQL):** menyimpan data user, lapangan, booking, match  

---

## Perancangan Role & Hak Akses

### Admin
- Mengelola data user  
- Verifikasi lapangan  
- Monitoring aktivitas sistem  

### Owner (Pemilik Lapangan)
- Menambah & edit data lapangan  
- Mengatur jadwal & ketersediaan  
- Mengelola booking  

### Player (User/Penyewa)
- Melihat daftar lapangan  
- Booking lapangan  
- Membuat & bergabung public match  
- Memberikan rating & review  

**Implementasi Role:**  
Menggunakan satu tabel `users` dengan atribut `role` (`admin`, `owner`, `player`).

---

## Alur Pengembangan Sistem
1. **Authentication & Role Management**  
   - Login, register, logout  
   - Penentuan role & pembatasan akses  

2. **Admin Panel**  
   - Dashboard admin (Filament)  
   - Manajemen user & verifikasi lapangan  

3. **Owner System**  
   - Pengelolaan data lapangan  
   - Manajemen jadwal & slot  
   - Pengelolaan booking  

4. **Core Feature (Player)**  
   - Menampilkan daftar lapangan  
   - Proses booking  
   - Menampilkan jadwal  

5. **Public Match**  
   - Pembuatan match  
   - Bergabung ke match  
   - Menampilkan daftar match  

6. **Fitur Tambahan**  
   - Rating & review  
   - Favorite lapangan  
   - Integrasi WhatsApp  
   - Countdown timer  
   - Filter gender pada match  

---

## Pembagian Tugas Tim

### Backend Developer
- Fitur backend dengan Laravel  
- Database (MySQL)  
- Migration & model  
- Authentication & role management  
- Controller & business logic  
- Integrasi dengan frontend  

### Frontend Developer
- Implementasi UI → halaman web  
- HTML, CSS, JS, TailwindCSS  
- Integrasi dengan backend (Blade Laravel)  
- Optimasi tampilan responsif & user-friendly  

### Dokumentasi
- Menyusun dokumen **SRS**  
- Laporan pengembangan sistem  
- Manual book pengguna  
- Naskah presentasi  
- Konsistensi format & bahasa  
- Revisi sesuai feedback dosen  

### Diagram & Perancangan Sistem
- Use Case Diagram  
- Activity Diagram  
- Class Diagram  
- ERD  
- Textual analysis kebutuhan sistem  
- Menjaga kesesuaian diagram dengan implementasi  

---

## Kolaborasi Tim
- Backend dan Frontend terintegrasi via Blade Laravel  
- Dokumentasi sesuai hasil implementasi  
- Diagram diperbarui sesuai perkembangan sistem  
- Koordinasi berkala untuk sinkronisasi progress  

---

## Version Control (GitHub)
- Menyimpan source code terpusat  
- Melacak perubahan kode  
- Membagi tugas via repository  
- Bukti kontribusi individu dalam proyek PBL  

---

## Kesimpulan
Sistem **Spies Sport** dibangun dengan kombinasi teknologi **Laravel, Filament, TailwindCSS, MySQL** untuk efisiensi pengembangan dan kemudahan implementasi.  
Dengan arsitektur terstruktur, role yang jelas, serta strategi pengembangan bertahap, sistem ini diharapkan mampu memenuhi kebutuhan pengguna sekaligus mencapai target luaran proyek PBL.
