# Atturin — Community Event Coordination Platform

Atturin adalah platform manajemen dan koordinasi event komunitas yang membantu organizer mengelola peserta, pembayaran, slot event, dan attendance dalam satu workflow terintegrasi.

Platform ini dirancang untuk mengurangi kekacauan operasional yang sering terjadi pada event komunitas seperti:

- Pembayaran manual
- Spam chat penagihan
- Slot peserta yang tidak jelas
- Peserta _ghosting_
- Kesulitan tracking siapa yang sudah bayar
- Rekap event yang berantakan

Awalnya Atturin difokuskan untuk komunitas olahraga seperti futsal, mini tournament, sparring, dan trofeo. Namun, arsitektur sistemnya dirancang fleksibel agar dapat digunakan untuk berbagai jenis komunitas dan event lainnya seperti gathering, touring/sunmori, meetup, bukber, nobar, hingga event komunitas berbayar lainnya.

## Core Concept

**Atturin bukan marketplace olahraga.**

Atturin adalah **event coordination operating system** untuk komunitas. Fokus utamanya adalah membantu organizer menjalankan event tanpa chaos operasional.

## Masalah yang Diselesaikan

Sebelum menggunakan Atturin, organizer biasanya:

- Mencatat peserta secara manual
- Menagih pembayaran satu per satu
- Menggunakan spreadsheet terpisah
- Kesulitan tracking slot
- Kesulitan mengetahui siapa yang hadir
- Mengalami risiko nombok karena peserta cancel mendadak
- Menghabiskan banyak waktu untuk koordinasi administratif

Atturin menyederhanakan seluruh proses tersebut menjadi satu workflow digital.

## Workflow Utama

1.  **Organizer membuat event**
    Organizer menentukan:
    - Nama event
    - Tanggal
    - Lokasi
    - Kapasitas peserta
    - Harga
    - Sistem pembayaran
    - Aturan event

2.  **Peserta bergabung melalui link**
    Peserta cukup:
    - Membuka link event
    - Mengisi data sederhana seperti nama dan nomor
    - Melakukan pembayaran jika diperlukan
      _Tidak semua peserta harus memiliki akun agar flow tetap cepat dan ringan._

3.  **Sistem melakukan tracking otomatis**
    Atturin membantu organizer memantau:
    - Siapa yang sudah bayar
    - Siapa yang belum bayar
    - Slot yang tersisa
    - Waiting list
    - Attendance peserta
    - Status event secara real-time

## Sistem Payment & Wallet

Untuk mendukung banyak organizer dengan tujuan pembayaran berbeda-beda, Atturin dirancang menggunakan sistem **internal wallet**.

### Kenapa wallet diperlukan?

Karena Atturin memiliki model **multi-community, multi-organizer, dan multi-settlement**. Artinya, setiap komunitas atau organizer dapat memiliki tujuan pencairan dana yang berbeda.

Contoh:

- Komunitas A → pencairan ke admin A
- Komunitas B → pencairan ke admin B

Wallet digunakan sebagai:

- Penampung sementara dana transaksi
- Media pencatatan saldo organizer
- Pusat rekonsiliasi transaksi sebelum withdrawal

### Flow Payment

1.  Saat peserta membayar, payment gateway menerima pembayaran.
2.  Callback/webhook dikirim ke sistem Atturin.
3.  Status pembayaran peserta diperbarui otomatis.
4.  Saldo organizer bertambah ke wallet internal.
5.  Organizer dapat melakukan withdrawal ke rekening/e-wallet mereka.

### Keuntungan Model Wallet

- Mendukung banyak organizer dengan rekening berbeda.
- Memudahkan tracking transaksi.
- Mendukung sistem fee/platform commission.
- Mendukung settlement bertahap.
- Mengurangi kompleksitas split payment langsung.
- Lebih scalable untuk multi-community platform.

## Fitur Utama

- **Event Management**: Buat dan kelola event komunitas.
- **Participant Tracking**: Pantau peserta dan slot secara real-time.
- **Payment Coordination**: Tracking pembayaran otomatis.
- **Attendance System**: Tracking kehadiran peserta.
- **Waiting List Management**: Otomatisasi slot cadangan.
- **Organizer Dashboard**: Rekap event, peserta, dan pembayaran.
- **Wallet & Withdrawal**: Saldo organizer dan pencairan dana.
- **Automated Reminder**: Reminder pembayaran dan attendance.

## Target User

- **Primary**: Organizer komunitas, admin event, koordinator olahraga, komunitas touring, penyelenggara gathering.
- **Secondary**: Peserta event komunitas.

## Positioning

Atturin diposisikan sebagai **“community event operating system”**, bukan:

- Marketplace olahraga
- Aplikasi split bill biasa
- Atau sekadar sistem booking lapangan

## Nilai Utama

Atturin membantu organizer:

- **Mengurangi chaos**
- **Mengurangi risiko nombok**
- **Mempercepat koordinasi**
- **Menjalankan event komunitas dengan workflow yang lebih modern, terstruktur, dan transparan.**
