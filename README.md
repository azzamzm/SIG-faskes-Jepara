

# Dokumentasi Proyek: SIG Rute Terpendek Faskes Kabupaten Jepara

Sistem Informasi Geografis (SIG) ini dirancang untuk mengoptimalkan pencarian fasilitas kesehatan di Kabupaten Jepara menggunakan **Algoritma Dijkstra**. Sistem ini mengintegrasikan data spasial dengan antarmuka berbasis web untuk memberikan navigasi medis yang cepat dan akurat.

---

## 1. Ikhtisar Sistem (System Overview)

Sistem ini menggunakan arsitektur **Three-Tier** yang memisahkan antara penyimpanan data, logika server, dan antarmuka pengguna.

* **Frontend**: HTML5, CSS3, dan Leaflet.js (Peta Interaktif).
* **Backend**: PHP (Pemrosesan Data).
* **Database**: MySQL (Penyimpanan Koordinat Faskes).
* **Routing Engine**: Leaflet Routing Machine (Implementasi Dijkstra).

---

## 2. Struktur Modul & Logika Program

### A. Modul Koneksi (`koneksi.php`)

Modul ini bertanggung jawab menghubungkan aplikasi dengan database MySQL.

* **Logic**: Menggunakan fungsi `mysqli_connect` untuk membuka jalur komunikasi data.
* **Keamanan**: Disarankan untuk menggunakan variabel lingkungan atau menyembunyikan file ini dari repositori publik.

### B. Modul API Data Spasial (`get_data.php`)

Berfungsi sebagai jembatan data (Data Bridge).

* **Logic**: Mengambil seluruh data dari tabel `faskes`, mengubahnya menjadi format **JSON**, dan mengirimkannya ke frontend.
* **Output**: Array objek berisi nama faskes, latitude, dan longitude.

### C. Modul Antarmuka & Navigasi (`index.php`)

Ini adalah modul utama yang menangani visualisasi dan komputasi rute.

1. **Inisialisasi Peta**: Memuat tiles dari OpenStreetMap melalui Leaflet.js.
2. **Geolocation**: Mendeteksi titik koordinat awal () pengguna secara otomatis.
3. **Algoritma Dijkstra**:
* Sistem membangun graf berdasarkan jaringan jalan rill.
* Menghitung bobot akumulatif terkecil dari posisi pengguna ke destinasi yang dipilih.
* **Visualisasi**: Menampilkan jalur optimal dalam bentuk *polyline* merah.



---

## 3. Fitur Utama

| Fitur | Deskripsi | Logika Teknis |
| --- | --- | --- |
| **Auto-Location** | Deteksi posisi pengguna saat ini. | Geolocation API |
| **Interactive Map** | Peta interaktif Kabupaten Jepara. | Leaflet.js & OSM |
| **Smart Routing** | Pencarian rute terpendek otomatis. | Dijkstra Algorithm |
| **Info Summary** | Detail jarak (km) dan waktu tempuh (menit). | Routing Summary Logic |

---

## 4. Tampilan Antarmuka (UI)

* **Header**: Menampilkan judul sistem untuk identitas aplikasi.
* **Control Panel**: Terdiri dari *Dropdown Select* untuk memilih faskes tujuan.
* **Map Area**: Luas peta yang responsif untuk navigasi seluler maupun desktop.
* **Result Box**: Menampilkan hasil kalkulasi jarak dan estimasi waktu setelah rute ditemukan.

---

## 5. Panduan Instalasi (Setup)

1. **Persiapan Database**:
* Buat database bernama `sig_jepara`.
* Import file `.sql` (jika tersedia) atau buat tabel `faskes` dengan kolom `id`, `nama`, `lat`, `lng`.


2. **Konfigurasi Koneksi**:
* Ubah file `koneksi.php` sesuai dengan kredensial database lokal Anda (Host, User, Pass).


3. **Deploy**:
* Letakkan folder proyek di dalam direktori `htdocs` (XAMPP) atau `www` (WampServer).
* Akses melalui browser di alamat `http://localhost/NAMA FOLDER ANDA`.



---

## 6. Referensi Ilmiah

Proyek ini didasarkan pada penelitian yang dipublikasikan di **Jurnal JTIK (Jurnal Teknologi Informasi dan Komunikasi)**, mengacu pada prinsip optimasi rute terpendek yang dikembangkan oleh:

* Zaki, A. (2017) mengenai Teori Dijkstra.
* Musyarraf, F. F., et al. (2024) mengenai Pemetaan Faskes di Jepara.

---

**Kontribusi & Lisensi**
Proyek ini bersifat Open Source untuk tujuan pendidikan dan pengembangan layanan publik.

---

