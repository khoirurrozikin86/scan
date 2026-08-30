# 🎟️ ScanTiket

**ScanTiket** adalah sistem manajemen dan validasi tiket berbasis QR Code yang digunakan untuk melakukan proses scanning tiket pada berbagai outlet atau wahana.

Sistem mendukung **Camera Scanner** dan **Barcode Scanner**, dilengkapi dengan sistem permission berdasarkan outlet, pencatatan histori scan, laporan transaksi, filtering berdasarkan tanggal, serta export data ke Excel.

---

## ✨ Features

### 📷 Camera Scanner

Melakukan scanning QR Code menggunakan kamera perangkat.

- Scan QR Code menggunakan kamera
- Pemilihan outlet sebelum melakukan scan
- Proses validasi tiket secara otomatis
- Notifikasi tiket valid atau ditolak
- Otomatis mencatat waktu scan
- Menampilkan 10 scan terakhir
- Scan method tersimpan sebagai `camera`

---

### 🔳 Barcode Scanner

Mendukung barcode scanner yang bekerja seperti keyboard input.

- Scan barcode menggunakan perangkat scanner
- Tidak diperuntukkan untuk input manual
- Validasi tiket secara realtime
- Pemilihan outlet
- Notifikasi hasil scanning
- Otomatis membersihkan input setelah scan
- Scan method tersimpan sebagai `scanner`

---

### 🎫 Ticket Validation

Sistem melakukan validasi terhadap tiket sebelum menerima scan.

Validasi mencakup:

- QR Code / barcode tiket
- Nomor tiket
- Jenis tiket
- Status tiket
- Outlet yang melakukan scan
- Riwayat penggunaan tiket

### 🔒 One-Time Ticket Usage

Setiap tiket hanya dapat digunakan **satu kali**.

Contoh:

```text
Tiket 103501
        ↓
Scan di Wahana A
        ↓
VALID
        ↓
Tiket menjadi USED
        ↓
Scan kembali di Wahana A
        ↓
DITOLAK
        ↓
Scan di Wahana B
        ↓
DITOLAK
