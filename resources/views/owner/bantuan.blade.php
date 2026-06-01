@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pusat Bantuan - Spiessport Portal Pemilik</title>
    @vite(['resources/css/app.css', 'resources/css/owner-dashboard.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
        body { background-color: #f7f2f2; }
        .help-hero { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); }
        .help-card { transition: all 0.3s ease; }
        .help-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(220,38,38,0.12); }
        .faq-btn { transition: all 0.2s; }
        .faq-btn:hover { background: #fef2f2; }
        .faq-content { display: none; }
        .faq-content.is-open { display: block; }
    </style>
</head>
<body>
<div class="dashboard-layout">
    @include('owner.navbar')

    <main class="main-content">

        {{-- Hero --}}
        <div class="help-hero" style="border-radius:20px;padding:48px 36px;margin-top:24px;text-align:center;position:relative;overflow:hidden;">
            <div style="position:absolute;inset:0;opacity:0.08;pointer-events:none;background-image:radial-gradient(circle at 2px 2px, #fff 1px, transparent 0);background-size:36px 36px;"></div>
            <div style="position:relative;z-index:1;max-width:600px;margin:0 auto;">
                <h1 style="font-size:30px;font-weight:700;color:#fff;margin:0 0 8px 0;">Ada yang bisa kami bantu?</h1>
                <p style="font-size:14px;color:rgba(255,255,255,0.85);margin:0;">Cari panduan penggunaan, solusi kendala, atau hubungi tim dukungan kami.</p>
            </div>
        </div>

        {{-- Categories --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin-top:28px;">
            <div class="help-card" style="background:#fff;border-radius:16px;padding:24px;border:1px solid #e2e8f0;cursor:pointer;">
                <div style="width:48px;height:48px;border-radius:12px;background:#fef2f2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:20px;margin-bottom:16px;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <h3 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 6px 0;">Akun & Profil</h3>
                <p style="font-size:13px;color:#64748b;margin:0;line-height:1.5;">Kelola informasi pribadi, ganti kata sandi, dan pengaturan akses pemilik.</p>
            </div>
            <div class="help-card" style="background:#fff;border-radius:16px;padding:24px;border:1px solid #e2e8f0;cursor:pointer;">
                <div style="width:48px;height:48px;border-radius:12px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;color:#006c49;font-size:20px;margin-bottom:16px;">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <h3 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 6px 0;">Pembayaran</h3>
                <p style="font-size:13px;color:#64748b;margin:0;line-height:1.5;">Informasi tentang faktur, metode pembayaran, dan cara pencairan dana.</p>
            </div>
            <div class="help-card" style="background:#fff;border-radius:16px;padding:24px;border:1px solid #e2e8f0;cursor:pointer;">
                <div style="width:48px;height:48px;border-radius:12px;background:#fffbeb;display:flex;align-items:center;justify-content:center;color:#7f4f00;font-size:20px;margin-bottom:16px;">
                    <i class="fa-solid fa-futbol"></i>
                </div>
                <h3 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 6px 0;">Pengaturan Lapangan</h3>
                <p style="font-size:13px;color:#64748b;margin:0;line-height:1.5;">Cara menambah lapangan baru, mengubah harga, dan mengatur slot waktu.</p>
            </div>
            <div class="help-card" style="background:#fff;border-radius:16px;padding:24px;border:1px solid #e2e8f0;cursor:pointer;">
                <div style="width:48px;height:48px;border-radius:12px;background:#fef2f2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:20px;margin-bottom:16px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 6px 0;">Masalah Teknis</h3>
                <p style="font-size:13px;color:#64748b;margin:0;line-height:1.5;">Solusi untuk kendala akses aplikasi, sinkronisasi data, atau kesalahan sistem.</p>
            </div>
        </div>

        {{-- FAQ --}}
        <div style="background:#fff;border-radius:20px;padding:32px;margin-top:28px;border:1px solid #e2e8f0;">
            <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
                <div>
                    <h2 style="font-size:22px;font-weight:700;color:#1e293b;margin:0 0 4px 0;">Pertanyaan Populer</h2>
                    <p style="font-size:13px;color:#64748b;margin:0;">Jawaban instan untuk pertanyaan yang sering diajukan.</p>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                    <button class="faq-btn" onclick="toggleFaq(this)" style="width:100%;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:none;border:none;cursor:pointer;font-size:15px;font-weight:600;color:#1e293b;text-align:left;font-family:inherit;">
                        <span>Bagaimana cara mengubah harga sewa lapangan?</span>
                        <i class="fa-solid fa-chevron-down" style="color:#94a3b8;transition:transform 0.2s;"></i>
                    </button>
                    <div class="faq-content" style="padding:0 20px 16px 20px;font-size:13px;color:#64748b;line-height:1.6;border-top:1px solid #e2e8f0;padding-top:12px;">
                        Anda dapat mengubah harga melalui menu Kelola Lapangan, pilih lapangan yang diinginkan, lalu klik Ubah Detail. Harga akan langsung diperbarui di portal pelanggan setelah Anda menyimpan perubahan.
                    </div>
                </div>
                <div style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                    <button class="faq-btn" onclick="toggleFaq(this)" style="width:100%;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:none;border:none;cursor:pointer;font-size:15px;font-weight:600;color:#1e293b;text-align:left;font-family:inherit;">
                        <span>Bagaimana cara mencairkan pendapatan?</span>
                        <i class="fa-solid fa-chevron-down" style="color:#94a3b8;transition:transform 0.2s;"></i>
                    </button>
                    <div class="faq-content" style="padding:0 20px 16px 20px;font-size:13px;color:#64748b;line-height:1.6;border-top:1px solid #e2e8f0;padding-top:12px;">
                        Pendapatan dapat dicairkan melalui menu Finance - Pencairan Dana. Pastikan Anda telah melengkapi data rekening bank di profil Anda. Proses verifikasi biasanya memakan waktu 1-3 hari kerja.
                    </div>
                </div>
                <div style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                    <button class="faq-btn" onclick="toggleFaq(this)" style="width:100%;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:none;border:none;cursor:pointer;font-size:15px;font-weight:600;color:#1e293b;text-align:left;font-family:inherit;">
                        <span>Bagaimana cara membatalkan pesanan yang sudah dibayar?</span>
                        <i class="fa-solid fa-chevron-down" style="color:#94a3b8;transition:transform 0.2s;"></i>
                    </button>
                    <div class="faq-content" style="padding:0 20px 16px 20px;font-size:13px;color:#64748b;line-height:1.6;border-top:1px solid #e2e8f0;padding-top:12px;">
                        Pembatalan dapat dilakukan melalui menu Pengelolaan Pesanan. Klik pada pesanan yang dimaksud dan pilih opsi Batalkan Pesanan. Harap perhatikan kebijakan pengembalian dana yang berlaku di tempat Anda.
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact --}}
        <div style="background:#fff;border-radius:20px;padding:36px;margin-top:28px;border:1px solid #e2e8f0;display:flex;flex-direction:row;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap;position:relative;overflow:hidden;">
            <div style="position:absolute;right:0;top:0;width:120px;height:120px;background:rgba(220,38,38,0.04);border-radius:50%;transform:translate(40%,-40%);"></div>
            <div style="max-width:480px;">
                <h2 style="font-size:22px;font-weight:700;color:#1e293b;margin:0 0 6px 0;">Masih butuh bantuan?</h2>
                <p style="font-size:13px;color:#64748b;margin:0;">Tim dukungan kami siap membantu Anda kapan saja melalui saluran komunikasi yang tersedia.</p>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <a href="https://wa.me/#" style="display:inline-flex;align-items:center;gap:8px;background:#25D366;color:#fff;padding:12px 24px;border-radius:12px;font-weight:700;font-size:14px;text-decoration:none;">
                    <i class="fa-brands fa-whatsapp"></i> Hubungi via WhatsApp
                </a>
                <a href="mailto:support@spiessport.com" style="display:inline-flex;align-items:center;gap:8px;background:#1e293b;color:#fff;padding:12px 24px;border-radius:12px;font-weight:700;font-size:14px;text-decoration:none;">
                    <i class="fa-solid fa-envelope"></i> Kirim Email
                </a>
            </div>
        </div>
    </main>
</div>

@include('owner.faq-popup')

<script>
function toggleFaq(btn) {
    var content = btn.nextElementSibling;
    var icon = btn.querySelector('.fa-chevron-down');
    var isOpen = content.classList.contains('is-open');

    document.querySelectorAll('.faq-btn').forEach(function(b) {
        b.nextElementSibling.classList.remove('is-open');
        b.querySelector('.fa-chevron-down').style.transform = 'rotate(0deg)';
        b.parentElement.style.borderColor = '#e2e8f0';
    });

    if (!isOpen) {
        content.classList.add('is-open');
        icon.style.transform = 'rotate(180deg)';
        btn.parentElement.style.borderColor = '#dc2626';
    }
}
</script>
</body>
</html>
