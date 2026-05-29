<div id="faqPopup" style="display:none; position:fixed; bottom:90px; right:24px; width:340px; max-width:calc(100vw - 48px); background:white; border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,.2); z-index:1000; overflow:hidden;">
    <div style="background:#EB5436; color:white; padding:16px 20px; display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:10px;">
            <i class="fa-solid fa-headset" style="font-size:20px;color:white;"></i>
            <span style="font-weight:700; font-size:15px;">Pusat Bantuan</span>
        </div>
        <span onclick="toggleFaqPopup()" style="cursor:pointer; font-size:20px; line-height:1; color:white;">&times;</span>
    </div>
    <div style="padding:16px 20px;">
        <p style="font-size:13px; color:#666; margin-bottom:12px;">Ada yang bisa kami bantu?</p>
        <div onclick="faqAnswer('booking')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); margin-bottom:8px; cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span style="color:#EB5436; font-size:20px;">📅</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Cara Booking</div><div style="font-size:11px; color:#888;">Panduan memesan lapangan</div></div>
        </div>
        <div onclick="faqAnswer('join_match')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); margin-bottom:8px; cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span style="color:#EB5436; font-size:20px;">👥</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Cara Join Public Match</div><div style="font-size:11px; color:#888;">Bergabung pertandingan publik</div></div>
        </div>
        <div onclick="faqAnswer('payment')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); margin-bottom:8px; cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span style="color:#EB5436; font-size:20px;">💳</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Cara Pembayaran</div><div style="font-size:11px; color:#888;">Informasi metode pembayaran</div></div>
        </div>
        <div onclick="faqAnswer('cs')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span style="color:#EB5436; font-size:20px;">🎧</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Hubungi Customer Service</div><div style="font-size:11px; color:#888;">Chat dengan admin via WhatsApp</div></div>
        </div>
    </div>
    <div id="faqAnswerBox" style="display:none; padding:16px 20px; border-top:1px solid rgba(0,0,77,.06); background:#f8fafc;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <span style="font-weight:700; font-size:13px; color:#02025b;" id="faqAnswerTitle"></span>
            <span onclick="closeFaqAnswer()" style="cursor:pointer; font-size:16px; color:#999;">&times;</span>
        </div>
        <p style="font-size:13px; color:#555; line-height:1.6; white-space:pre-line;" id="faqAnswerText"></p>
    </div>
</div>

<script>
function toggleFaqPopup() {
    var popup = document.getElementById('faqPopup');
    popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
    document.getElementById('faqAnswerBox').style.display = 'none';
}
function faqAnswer(type) {
    var titleEl = document.getElementById('faqAnswerTitle');
    var textEl = document.getElementById('faqAnswerText');
    var boxEl = document.getElementById('faqAnswerBox');
    var answers = {
        booking: { title: 'Cara Booking', text: '1. Pilih lapangan yang kamu inginkan.\n2. Pilih tanggal dan jam yang tersedia.\n3. Klik "Pesan" dan ikuti instruksi pembayaran.\n4. Laporkan pembayaran ke owner untuk konfirmasi.\n5. Setelah dikonfirmasi, booking kamu aktif!' },
        join_match: { title: 'Cara Join Public Match', text: '1. Buka halaman "Cari Tim".\n2. Geser kartu pertandingan yang tersedia.\n3. Klik "Bergabung" pada pertandingan yang diinginkan.\n4. Lanjutkan pembayaran kontribusi jika ada.\n5. Tunggu konfirmasi dari host pertandingan.' },
        payment: { title: 'Cara Pembayaran', text: 'Pembayaran dilakukan dengan transfer ke rekening owner lapangan. Setelah transfer, laporkan pembayaran melalui halaman detail booking. Owner akan mengkonfirmasi pembayaran kamu.' },
        cs: { title: 'Hubungi Customer Service', text: '' }
    };
    var answer = answers[type];
    if (!answer) return;
    titleEl.textContent = answer.title;
    textEl.textContent = answer.text;
    boxEl.style.display = 'block';
    if (type === 'cs') {
        textEl.style.display = 'none';
        boxEl.innerHTML = '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;"><span style="font-weight:700; font-size:13px; color:#02025b;">Hubungi Customer Service</span><span onclick="closeFaqAnswer()" style="cursor:pointer; font-size:16px; color:#999;">&times;</span></div><p style="font-size:13px; color:#555; margin-bottom:12px;">Kamu akan dihubungkan dengan admin kami melalui WhatsApp.</p><a href="https://wa.me/6281234567890?text=Halo%20Spies%20Sport%2C%20saya%20butuh%20bantuan" target="_blank" style="display:block; text-align:center; background:#25D366; color:white; padding:12px; border-radius:12px; font-weight:700; text-decoration:none;">&#x1F4AC; Chat WhatsApp</a>';
    } else {
        textEl.style.display = 'block';
    }
}
function closeFaqAnswer() { document.getElementById('faqAnswerBox').style.display = 'none'; }
</script>