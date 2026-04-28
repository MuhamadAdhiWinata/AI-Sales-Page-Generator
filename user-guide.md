# User Guide – Panduan Penggunaan AI Sales Page Generator

## 1️⃣ Membuat Akun & Masuk
1. Buka aplikasi di browser, misalnya `http://localhost:3000`.
2. Klik **Register** pada halaman landing.
3. Isi **Nama**, **Email**, dan **Password**, lalu tekan **Register**.
4. Setelah berhasil, Anda diarahkan ke halaman login. Masukkan email & password, klik **Login**. Token Sanctum akan tersimpan di `localStorage` dan dipakai untuk semua request API.

## 2️⃣ Membuat Halaman Penjualan Baru
1. Pada dashboard, pilih **Create New Sales Page**.
2. Isi **Form Produk** yang terstruktur:
   - **Nama Produk**
   - **Deskripsi Singkat**
   - **Fitur-fitur** (tambahkan sebanyak yang diperlukan)
   - **Target Audience**
   - **Harga**
   - **Unique Selling Point**
   - **Tone / Gaya Bahasa**
3. Tekan tombol **Generate**.
4. Sistem akan meng‑enqueue `GenerateSalesPageJob`. Selama proses, Anda akan melihat spinner dan status **Processing**.

## 3️⃣ Melihat & Menyunting Hasil
- Setelah job selesai, halaman yang di‑generate muncul dalam sebuah **iframe** di sebelah kanan. Iframe menggunakan `srcdoc` sehingga styling AI tidak bentrok dengan UI utama.
- Untuk meng‑edit, klik ikon **Edit with AI** (panel melayang muncul dari kanan).
- Ketik instruksi yang jelas, misalnya "Ubah warna tombol menjadi biru pastel" atau "Tambahkan bagian testimoni pelanggan".
- Tekan **Apply** atau `Ctrl+Enter`. Sistem akan meng‑enqueue `RefineSalesPageJob` yang memperbarui `content_html`.
- Iframe otomatis memperbarui ketika job selesai (status berubah menjadi **Completed**).

## 4️⃣ Riwayat & Manajemen Halaman
- Di menu **History**, Anda dapat melihat semua halaman yang pernah dibuat.
- Setiap entri menampilkan **Nama Produk**, **Tanggal Pembuatan**, dan **Status** (Processing / Completed / Failed).
- Klik entri untuk membuka detail, men‑preview, atau melakukan **Refine** lagi.
- Tombol **Delete** menghapus halaman secara permanen (hanya milik Anda).

## 5️⃣ Ekspor & Publikasi
- Pada halaman detail, terdapat tombol **Export HTML** yang mengunduh file `sales_page.html` siap di‑upload ke hosting Anda.
- Anda juga dapat men‑copy kode iframe dan men‑paste‑kan ke CMS lain bila diperlukan.

## 6️⃣ Tips Penggunaan
- **Instruksi AI**: beri perintah yang spesifik dan singkat. Contoh bagus: "Ubah heading pertama menjadi ukuran h2 dengan warna #2a2a2a".
- **Batasan**: Fitur *refine* hanya dapat mengedit konten yang di‑generate pada halaman yang sama; tidak dapat mengakses halaman milik user lain.
- **Refresh**: Jika iframe tidak langsung ter‑update, klik tombol **Refresh Preview** di panel edit.
- **Support**: Untuk pertanyaan atau bug, hubungi tim melalui link **Help** pada footer.

---

*Panduan ini ditujukan untuk pengguna akhir, memandu mereka melalui seluruh alur penggunaan aplikasi mulai dari registrasi hingga ekspor halaman penjualan.*
