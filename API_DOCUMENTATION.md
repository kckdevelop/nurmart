# DOKUMENTASI API BACKEND NURMART (Laravel 12)

Dokumentasi lengkap mengenai arsitektur antarmuka pemrograman aplikasi (API), format request/response, hak akses (RBAC), dan contoh integrasi untuk sistem manajemen kasir dan inventaris Toko NURMART.

---

## 1. Panduan Server & Koneksi

- **Base URL Pengembangan (Lokal)**: `http://localhost:8000/api` atau `http://10.0.2.2:8000/api` (Android Emulator)
- **Base URL Server Jaringan (LAN/HP Fisik)**: `http://<IP_KOMPUTER_SERVER>:8000/api`
- **Tipe Komunikasi**: RESTful API JSON over HTTP
- **Default Port**: `8000` (dijalankan melalui `php artisan serve --host=0.0.0.0 --port=8000`)
- **Autentikasi Header**:
  ```http
  Authorization: Bearer <token_sanctum_anda>
  Accept: application/json
  ```
  *(Catatan: Header `Authorization` tidak diperlukan untuk endpoint publik seperti `/public/*`, `/login`, dan cetak struk via URL `/penjualan/cetak-struk/{no_faktur}`)*

---

## 2. Format Respon JSON Standar

Semua respon dari API NURMART dibungkus dalam format seragam:

### 2.1 Respon Berhasil (Success)
```json
{
  "success": true,
  "message": "Pesan deskriptif aksi yang berhasil.",
  "data": { ... } // Objek atau Array data
}
```

### 2.2 Respon Validasi Gagal (HTTP 422 Unprocessable Entity)
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "nama_field": [
      "Pesan error spesifik pada field tersebut."
    ]
  }
}
```

### 2.3 Respon Tidak Diizinkan / Autentikasi Gagal (HTTP 401 & 403)
```json
{
  "success": false,
  "message": "Unauthenticated." // atau "Akses ditolak. Anda tidak memiliki izin."
}
```

### 2.4 Respon Not Found (HTTP 404)
```json
{
  "success": false,
  "message": "Data tidak ditemukan."
}
```

---

## 3. Manajemen User & Hak Akses (Role-Based Access Control)

Aplikasi memiliki 3 level hak akses (*Role*):

1. **`super_admin`**:
   - Memiliki akses penuh tanpa batasan ke seluruh endpoint sistem, termasuk konfigurasi toko, kelola user, modifikasi data master, stok opname, reset data, dan laporan finansial.
2. **`admin`**:
   - Memiliki akses operasional penuh (barang, kategori, supplier, transaksi belanja barang, pemesanan online, dan laporan).
   - *Tidak dapat* mengelola pengguna lain (tambah/hapus admin/kasir) atau mengubah pengaturan sistem tingkat lanjut jika dibatasi.
3. **`kasir`**:
   - Dikhususkan untuk aktivitas Point of Sales (POS) di kasir: transaksi penjualan, cek ketersediaan stok barang, melihat riwayat transaksi sendiri, cetak ulang struk, dan mengubah kata sandi akun sendiri.

---

## 4. Matriks Akses Endpoint

| Modul / Fitur | Endpoint URL | Method | Kasir | Admin | Super Admin | Publik |
| :--- | :--- | :---: | :---: | :---: | :---: | :---: |
| **Autentikasi** | `/login` | POST | Ya | Ya | Ya | Ya |
| | `/logout` | POST | Ya | Ya | Ya | - |
| | `/me` | GET | Ya | Ya | Ya | - |
| | `/ubah-password` | POST | Ya | Ya | Ya | - |
| **Katalog Publik** | `/public/produk` | GET | Ya | Ya | Ya | Ya |
| | `/public/cek-stok` | POST | Ya | Ya | Ya | Ya |
| | `/public/pesanan` | POST | Ya | Ya | Ya | Ya |
| **Pengaturan Toko** | `/pengaturan` | GET | Ya | Ya | Ya | - |
| | `/pengaturan` | POST | - | - | Ya | - |
| **Manajemen User** | `/users` | GET, POST | - | - | Ya | - |
| | `/users/{id}` | GET, PUT, DELETE | - | - | Ya | - |
| **Kategori Barang** | `/kategori` | GET | Ya | Ya | Ya | - |
| | `/kategori` | POST | - | Ya | Ya | - |
| | `/kategori/{id}` | PUT, DELETE | - | Ya | Ya | - |
| **Supplier** | `/supplier` | GET | - | Ya | Ya | - |
| | `/supplier` | POST | - | Ya | Ya | - |
| | `/supplier/{id}` | GET, PUT, DELETE | - | Ya | Ya | - |
| **Master Barang** | `/barang` | GET | Ya | Ya | Ya | - |
| | `/barang/{id}` | GET | Ya | Ya | Ya | - |
| | `/barang` | POST | - | Ya | Ya | - |
| | `/barang/{id}` | POST / PUT | - | Ya | Ya | - |
| | `/barang/{id}` | DELETE | - | Ya | Ya | - |
| | `/barang/barcode/{barcode}` | GET | Ya | Ya | Ya | - |
| | `/barang/{id}/update-stok` | POST | - | Ya | Ya | - |
| **Belanja / Kulakan**| `/belanja` | GET, POST | - | Ya | Ya | - |
| | `/belanja/{id}` | GET, DELETE | - | Ya | Ya | - |
| **POS / Penjualan** | `/penjualan` | GET, POST | Ya | Ya | Ya | - |
| | `/penjualan/{id}` | GET | Ya | Ya | Ya | - |
| | `/penjualan/faktur/{no_faktur}` | GET | Ya | Ya | Ya | - |
| | `/penjualan/cetak-struk/{no_faktur}` | GET | Ya | Ya | Ya | Ya |
| **Kelola Pesanan** | `/pesanan` | GET | - | Ya | Ya | - |
| | `/pesanan/{id}` | GET | - | Ya | Ya | - |
| | `/pesanan/{id}/status` | PUT | - | Ya | Ya | - |
| | `/pesanan/{id}` | DELETE | - | Ya | Ya | - |
| | `/pesanan/{id}/cetak-struk` | GET | Ya | Ya | Ya | Ya |
| **Laporan & Dashboard**| `/laporan/dashboard` | GET | - | Ya | Ya | - |
| | `/laporan/penjualan` | GET | - | Ya | Ya | - |
| | `/laporan/belanja` | GET | - | Ya | Ya | - |
| | `/laporan/stok-menipis` | GET | Ya | Ya | Ya | - |
| | `/laporan/keuntungan` | GET | - | - | Ya | - |

---

## 5. Rincian API Endpoint

### 5.1 Auth & Profil

#### 1. Login Akun
- **Endpoint**: `POST /api/login`
- **Akses**: Publik
- **Request Body**:
  ```json
  {
    "username": "kasir1",
    "password": "password123"
  }
  ```
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Login berhasil",
    "data": {
      "user": {
        "id": 2,
        "name": "Siti Kasir",
        "username": "kasir1",
        "role": "kasir",
        "is_active": true
      },
      "token": "1|NUrMartSecureTokenGeneratedKey..."
    }
  }
  ```

#### 2. Info Profil Login
- **Endpoint**: `GET /api/me`
- **Akses**: Kasir, Admin, Super Admin
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Data profil ditemukan",
    "data": {
      "id": 1,
      "name": "Administrator",
      "username": "admin",
      "role": "super_admin",
      "is_active": true
    }
  }
  ```

#### 3. Ganti Password Akun Sendiri
- **Endpoint**: `POST /api/ubah-password`
- **Akses**: Kasir, Admin, Super Admin
- **Request Body**:
  ```json
  {
    "current_password": "password123",
    "new_password": "newPassword321",
    "new_password_confirmation": "newPassword321"
  }
  ```
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Password berhasil diubah."
  }
  ```

#### 4. Logout Akun
- **Endpoint**: `POST /api/logout`
- **Akses**: Kasir, Admin, Super Admin
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Logout berhasil dan token telah dihapus."
  }
  ```

---

### 5.2 Pengaturan Toko

#### 1. Ambil Pengaturan Toko
- **Endpoint**: `GET /api/pengaturan`
- **Akses**: Kasir, Admin, Super Admin
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "data": {
      "nama_toko": "NURMART",
      "alamat": "Jl. Raya Kalisat No. 12, Jember",
      "no_telepon": "081234567890",
      "pesan_footer_struk": "Terima kasih telah berbelanja di NURMART!",
      "logo_url": "http://localhost:8000/storage/pengaturan/logo.png"
    }
  }
  ```

#### 2. Update Pengaturan Toko
- **Endpoint**: `POST /api/pengaturan`
- **Akses**: Super Admin
- **Content-Type**: `multipart/form-data`
- **Form Data**:
  - `nama_toko` (string, opsional)
  - `alamat` (string, opsional)
  - `no_telepon` (string, opsional)
  - `pesan_footer_struk` (string, opsional)
  - `logo` (file gambar: jpeg, png, jpg, max 2048 KB, opsional)

---

### 5.3 Manajemen Pengguna (User Management)

> **Akses Khusus**: `super_admin`

#### 1. Daftar Pengguna
- **Endpoint**: `GET /api/users`
- **Query Params**: `?search=&role=&is_active=`

#### 2. Tambah Pengguna Baru
- **Endpoint**: `POST /api/users`
- **Request Body**:
  ```json
  {
    "name": "Ahmad Kasir",
    "username": "ahmad_kasir",
    "password": "PasswordKasir123",
    "role": "kasir",
    "is_active": true
  }
  ```

#### 3. Update Pengguna
- **Endpoint**: `PUT /api/users/{id}`
- **Request Body**:
  ```json
  {
    "name": "Ahmad Kasir Baru",
    "username": "ahmad_kasir",
    "role": "kasir",
    "is_active": true,
    "password": "opsional_diisi_jika_ganti"
  }
  ```

#### 4. Hapus Pengguna
- **Endpoint**: `DELETE /api/users/{id}`

---

### 5.4 Kategori Barang

#### 1. Daftar Kategori
- **Endpoint**: `GET /api/kategori`
- **Akses**: Kasir, Admin, Super Admin
- **Query Params**: `?search=&per_page=`

#### 2. Tambah Kategori
- **Endpoint**: `POST /api/kategori`
- **Akses**: Admin, Super Admin
- **Request Body**:
  ```json
  {
    "nama_kategori": "Minuman Dingin",
    "deskripsi": "Aneka minuman botol dan kaleng"
  }
  ```

#### 3. Update Kategori
- **Endpoint**: `PUT /api/kategori/{id}`
- **Akses**: Admin, Super Admin
- **Request Body**:
  ```json
  {
    "nama_kategori": "Minuman Kemasan",
    "deskripsi": "Aneka minuman botol, dus, dan kaleng"
  }
  ```

#### 4. Hapus Kategori
- **Endpoint**: `DELETE /api/kategori/{id}`
- **Akses**: Admin, Super Admin

---

### 5.5 Supplier

#### 1. Daftar Supplier
- **Endpoint**: `GET /api/supplier`
- **Akses**: Admin, Super Admin
- **Query Params**:
  - `search` (string, opsional): Cari berdasarkan nama supplier atau kontak
  - `per_page` (integer, opsional, default: 15)

#### 2. Tambah Supplier
- **Endpoint**: `POST /api/supplier`
- **Akses**: Admin, Super Admin
- **Request Body**:
  ```json
  {
    "nama_supplier": "PT Sumber Alfaria Distribusi",
    "no_telepon": "081298765432",
    "alamat": "Kawasan Industri Rungkut Surabaya"
  }
  ```

#### 3. Detail Supplier
- **Endpoint**: `GET /api/supplier/{id}`
- **Akses**: Admin, Super Admin
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Data supplier berhasil diambil",
    "data": {
      "id": 1,
      "nama_supplier": "PT Sumber Alfaria Distribusi",
      "no_telepon": "081298765432",
      "alamat": "Kawasan Industri Rungkut Surabaya",
      "created_at": "2026-09-24T06:00:00.000000Z",
      "updated_at": "2026-09-24T06:00:00.000000Z",
      "belanjas": [
        {
          "id": 5,
          "no_faktur_pembelian": "BEL-20260927-001",
          "total_belanja": 1500000,
          "tanggal_belanja": "2026-09-27"
        }
      ]
    }
  }
  ```

#### 4. Update Supplier
- **Endpoint**: `PUT /api/supplier/{id}`
- **Akses**: Admin, Super Admin
- **Request Body**:
  ```json
  {
    "nama_supplier": "PT Sumber Alfaria Distribusi Baru",
    "no_telepon": "081298765432",
    "alamat": "Jl. Ahmad Yani No. 50 Surabaya"
  }
  ```

#### 5. Hapus Supplier
- **Endpoint**: `DELETE /api/supplier/{id}`
- **Akses**: Admin, Super Admin

---

### 5.6 Master Barang

#### 1. Daftar Barang (Pagination, Filter & Pencarian)
- **Endpoint**: `GET /api/barang`
- **Akses**: Kasir, Admin, Super Admin
- **Query Params**:
  - `search` (string, opsional): Pencarian nama barang atau kode barcode
  - `kategori_id` (integer, opsional): Filter berdasarkan ID kategori
  - `stok_menipis` (boolean: 1/0, opsional): Hanya tampilkan barang yang `stok <= stok_minimum`
  - `per_page` (integer, opsional, default: 15)

#### 2. Detail Barang
- **Endpoint**: `GET /api/barang/{id}`
- **Akses**: Kasir, Admin, Super Admin

#### 3. Cari Barang by Barcode
- **Endpoint**: `GET /api/barang/barcode/{barcode}`
- **Akses**: Kasir, Admin, Super Admin
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Barang ditemukan",
    "data": {
      "id": 12,
      "kategori_id": 2,
      "nama_barang": "Indomie Goreng Original 85g",
      "barcode": "8998866200114",
      "harga_beli": 2800,
      "harga_jual": 3500,
      "stok": 120,
      "stok_minimum": 20,
      "satuan": "pcs",
      "gambar_url": "http://localhost:8000/storage/barang/indomie.png",
      "kategori": {
        "id": 2,
        "nama_kategori": "Makanan Instan"
      }
    }
  }
  ```

#### 4. Tambah Barang Baru
- **Endpoint**: `POST /api/barang`
- **Akses**: Admin, Super Admin
- **Content-Type**: `multipart/form-data`
- **Body Fields**:
  - `kategori_id` (integer, required)
  - `nama_barang` (string, required, max:255)
  - `barcode` (string, nullable, unique)
  - `harga_beli` (numeric, required, min:0)
  - `harga_jual` (numeric, required, min:0)
  - `stok` (integer, required, min:0)
  - `stok_minimum` (integer, required, min:0)
  - `satuan` (string, required, max:50, e.g., 'pcs', 'pack', 'kg', 'dus')
  - `gambar` (file image: jpeg, png, jpg, max 2048 KB, opsional)

#### 5. Update Barang
- **Endpoint**: `POST /api/barang/{id}` *(disarankan dengan `_method: PUT` jika mengirim file)* atau `PUT /api/barang/{id}`
- **Akses**: Admin, Super Admin

#### 6. Hapus Barang
- **Endpoint**: `DELETE /api/barang/{id}`
- **Akses**: Admin, Super Admin

#### 7. Penyesuaian / Update Cepat Stok Barang
- **Endpoint**: `POST /api/barang/{id}/update-stok`
- **Akses**: Admin, Super Admin
- **Request Body**:
  ```json
  {
    "tipe": "tambah", // Pilihan: "tambah", "kurang", "set"
    "jumlah": 50,
    "keterangan": "Restock manual tambahan dari gudang samping"
  }
  ```

---

### 5.7 Transaksi Belanja / Kulakan (Restock)

#### 1. Daftar Riwayat Belanja
- **Endpoint**: `GET /api/belanja`
- **Akses**: Admin, Super Admin
- **Query Params**:
  - `start_date` (string, YYYY-MM-DD, opsional)
  - `end_date` (string, YYYY-MM-DD, opsional)
  - `supplier_id` (integer, opsional)
  - `per_page` (integer, opsional, default: 15)
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Data belanja berhasil diambil",
    "data": {
      "current_page": 1,
      "data": [
        {
          "id": 1,
          "supplier_id": 1,
          "user_id": 1,
          "no_faktur_pembelian": "FAK-SUPP-9921",
          "total_belanja": 750000,
          "tanggal_belanja": "2026-09-27",
          "keterangan": "Kulakan mingguan distributor",
          "supplier": {
            "id": 1,
            "nama_supplier": "PT Sumber Alfaria Distribusi"
          },
          "user": {
            "id": 1,
            "name": "Administrator"
          },
          "items_count": 2
        }
      ]
    }
  }
  ```

#### 2. Input Transaksi Belanja (Otomatis Tambah Stok & Update Harga Beli)
- **Endpoint**: `POST /api/belanja`
- **Akses**: Admin, Super Admin
- **Request Body**:
  ```json
  {
    "supplier_id": 1,
    "no_faktur_pembelian": "FAK-SUPP-9921", // opsional
    "tanggal_belanja": "2026-09-27",
    "keterangan": "Kulakan mingguan distributor",
    "items": [
      {
        "barang_id": 12,
        "jumlah": 100,
        "harga_beli": 2750
      },
      {
        "barang_id": 14,
        "jumlah": 20,
        "harga_beli": 12000
      }
    ]
  }
  ```

#### 3. Detail Transaksi Belanja
- **Endpoint**: `GET /api/belanja/{id}`
- **Akses**: Admin, Super Admin

#### 4. Batalkan / Hapus Transaksi Belanja
- **Endpoint**: `DELETE /api/belanja/{id}`
- **Akses**: Admin, Super Admin
- *(Catatan: Menghapus data belanja otomatis mengurangi kembali stok barang terkait)*

---

### 5.8 Transaksi Kasir / POS (Penjualan)

#### 1. Input Transaksi Penjualan Baru
- **Endpoint**: `POST /api/penjualan`
- **Akses**: Kasir, Admin, Super Admin
- **Request Body**:
  ```json
  {
    "metode_pembayaran": "tunai", // Pilihan: "tunai", "qris", "transfer"
    "bayar": 50000,
    "diskon": 0,
    "catatan": "Pelanggan umum",
    "items": [
      {
        "barang_id": 12,
        "jumlah": 4,
        "harga_satuan": 3500,
        "diskon": 0
      },
      {
        "barang_id": 14,
        "jumlah": 2,
        "harga_satuan": 15000,
        "diskon": 1000
      }
    ]
  }
  ```
- **Response (201 Created)**:
  ```json
  {
    "success": true,
    "message": "Transaksi penjualan berhasil disimpan",
    "data": {
      "id": 89,
      "no_faktur": "FK-20260927-0001",
      "user_id": 2,
      "tanggal": "2026-09-27T10:30:15.000000Z",
      "total_kotor": 44000,
      "diskon": 1000,
      "total_bersih": 43000,
      "bayar": 50000,
      "kembalian": 7000,
      "metode_pembayaran": "tunai",
      "catatan": "Pelanggan umum",
      "user": {
        "id": 2,
        "name": "Siti Kasir"
      },
      "items": [
        {
          "id": 150,
          "barang_id": 12,
          "nama_barang": "Indomie Goreng Original 85g",
          "harga_beli": 2750,
          "harga_satuan": 3500,
          "jumlah": 4,
          "diskon": 0,
          "subtotal": 14000
        },
        {
          "id": 151,
          "barang_id": 14,
          "nama_barang": "Minyak Goreng Bimoli 1L",
          "harga_beli": 12000,
          "harga_satuan": 15000,
          "jumlah": 2,
          "diskon": 1000,
          "subtotal": 29000
        }
      ]
    }
  }
  ```

#### 2. Daftar Riwayat Penjualan
- **Endpoint**: `GET /api/penjualan`
- **Akses**: Kasir, Admin, Super Admin
- **Query Params**:
  - `start_date` (YYYY-MM-DD, opsional)
  - `end_date` (YYYY-MM-DD, opsional)
  - `user_id` (integer, opsional - filter kasir tertentu)
  - `metode_pembayaran` (string, opsional)
  - `per_page` (integer, opsional, default: 15)

#### 3. Detail Penjualan by ID
- **Endpoint**: `GET /api/penjualan/{id}`
- **Akses**: Kasir, Admin, Super Admin

#### 4. Detail Penjualan by Nomor Faktur
- **Endpoint**: `GET /api/penjualan/faktur/{no_faktur}`
- **Akses**: Kasir, Admin, Super Admin

#### 5. Cetak / Unduh File Struk PDF
- **Endpoint**: `GET /penjualan/cetak-struk/{no_faktur}` *(Web Route)* atau `GET /api/penjualan/cetak-struk/{no_faktur}`
- **Akses**: Publik / Kasir / Admin
- **Response**: Stream file PDF (format kertas struk thermal 58mm/80mm)

---

### 5.9 Modul Katalog & Pemesanan Publik (Customer Online Order)

> **Catatan**: Endpoint di bawah ini menggunakan prefix `/public/` dan tidak memerlukan token autentikasi (*No Auth Required*).

#### 1. Katalog Produk Publik
- **Endpoint**: `GET /api/public/produk`
- **Akses**: Publik
- **Query Params**:
  - `kategori_id` (integer, opsional): Filter berdasarkan ID kategori
  - `search` (string, opsional): Cari nama barang atau barcode
  - `per_page` (integer, opsional, default: 15)
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Katalog produk berhasil diambil",
    "data": {
      "current_page": 1,
      "data": [
        {
          "id": 12,
          "kategori_id": 2,
          "nama_barang": "Indomie Goreng Original 85g",
          "barcode": "8998866200114",
          "harga_jual": 3500,
          "stok": 120,
          "satuan": "pcs",
          "gambar_url": "http://localhost:8000/storage/barang/indomie.png",
          "kategori": {
            "id": 2,
            "nama_kategori": "Makanan Instan"
          }
        }
      ]
    }
  }
  ```

#### 2. Cek Validasi Ketersediaan Stok Real-Time
- **Endpoint**: `POST /api/public/cek-stok`
- **Akses**: Publik
- **Request Body**:
  ```json
  {
    "items": [
      { "barang_id": 12, "jumlah": 5 },
      { "barang_id": 14, "jumlah": 10 }
    ]
  }
  ```
- **Response Jika Stok Mencukupi (200 OK)**:
  ```json
  {
    "success": true,
    "tersedia": true,
    "items": [
      {
        "barang_id": 12,
        "nama_barang": "Indomie Goreng Original 85g",
        "stok_tersedia": 120,
        "jumlah_pesan": 5,
        "status": "tersedia"
      }
    ]
  }
  ```
- **Response Jika Ada Stok Tidak Cukup (200 OK)**:
  ```json
  {
    "success": true,
    "tersedia": false,
    "message": "Beberapa item melebihi stok yang tersedia",
    "items": [
      {
        "barang_id": 14,
        "nama_barang": "Minyak Goreng Bimoli 1L",
        "stok_tersedia": 3,
        "jumlah_pesan": 10,
        "status": "kurang"
      }
    ]
  }
  ```

#### 3. Kirim Pemesanan Pelanggan
- **Endpoint**: `POST /api/public/pesanan`
- **Akses**: Publik
- **Request Body**:
  ```json
  {
    "nama_pemesan": "Budi Santoso",
    "no_telepon": "081234567890",
    "alamat": "Jl. Mawar No. 10, RT 02 RW 01, Jember",
    "catatan": "Tolong kirim sebelum jam 12 siang",
    "items": [
      {
        "barang_id": 12,
        "jumlah": 5
      },
      {
        "barang_id": 14,
        "jumlah": 2
      }
    ]
  }
  ```
- **Response (201 Created)**:
  ```json
  {
    "success": true,
    "message": "Pesanan berhasil dibuat. Kami akan segera memprosesnya!",
    "data": {
      "id": 15,
      "kode_pesanan": "ORD-20260927-0015",
      "nama_pemesan": "Budi Santoso",
      "no_telepon": "081234567890",
      "alamat": "Jl. Mawar No. 10, RT 02 RW 01, Jember",
      "total_harga": 47500,
      "status": "menunggu",
      "catatan": "Tolong kirim sebelum jam 12 siang",
      "created_at": "2026-09-27T11:00:00.000000Z",
      "items": [
        {
          "id": 30,
          "barang_id": 12,
          "nama_barang": "Indomie Goreng Original 85g",
          "harga_satuan": 3500,
          "jumlah": 5,
          "subtotal": 17500
        },
        {
          "id": 31,
          "barang_id": 14,
          "nama_barang": "Minyak Goreng Bimoli 1L",
          "harga_satuan": 15000,
          "jumlah": 2,
          "subtotal": 30000
        }
      ]
    }
  }
  ```

---

### 5.10 Manajemen Pesanan (Admin / Super Admin)

#### 1. Daftar Pesanan Masuk
- **Endpoint**: `GET /api/pesanan`
- **Akses**: Admin, Super Admin
- **Query Params**:
  - `status` (string, opsional): Filter status: `menunggu`, `diproses`, `selesai`, `dibatalkan`
  - `start_date` (string, YYYY-MM-DD, opsional)
  - `end_date` (string, YYYY-MM-DD, opsional)
  - `per_page` (integer, opsional, default: 15)
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Data pesanan berhasil diambil",
    "data": {
      "current_page": 1,
      "data": [
        {
          "id": 15,
          "kode_pesanan": "ORD-20260927-0015",
          "nama_pemesan": "Budi Santoso",
          "no_telepon": "081234567890",
          "total_harga": 47500,
          "status": "menunggu",
          "created_at": "2026-09-27T11:00:00.000000Z",
          "items_count": 2
        }
      ]
    }
  }
  ```

#### 2. Detail Pesanan
- **Endpoint**: `GET /api/pesanan/{id}`
- **Akses**: Admin, Super Admin
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Detail pesanan ditemukan",
    "data": {
      "id": 15,
      "kode_pesanan": "ORD-20260927-0015",
      "nama_pemesan": "Budi Santoso",
      "no_telepon": "081234567890",
      "alamat": "Jl. Mawar No. 10, RT 02 RW 01, Jember",
      "total_harga": 47500,
      "status": "menunggu",
      "catatan": "Tolong kirim sebelum jam 12 siang",
      "items": [
        {
          "id": 30,
          "barang_id": 12,
          "nama_barang": "Indomie Goreng Original 85g",
          "harga_satuan": 3500,
          "jumlah": 5,
          "subtotal": 17500
        }
      ]
    }
  }
  ```

#### 3. Update Status Pesanan
- **Endpoint**: `PUT /api/pesanan/{id}/status`
- **Akses**: Admin, Super Admin
- **Request Body**:
  ```json
  {
    "status": "diproses" // Pilihan: "menunggu", "diproses", "selesai", "dibatalkan"
  }
  ```
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Status pesanan berhasil diubah menjadi diproses.",
    "data": {
      "id": 15,
      "kode_pesanan": "ORD-20260927-0015",
      "status": "diproses"
    }
  }
  ```
  *(Catatan: Saat status diubah menjadi `selesai`, sistem secara otomatis memotong stok barang dan mencatatnya ke rekap laporan penjualan)*

#### 4. Hapus Pesanan
- **Endpoint**: `DELETE /api/pesanan/{id}`
- **Akses**: Admin, Super Admin
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Pesanan berhasil dihapus."
  }
  ```

#### 5. Cetak / Unduh File Struk Pesanan PDF
- **Endpoint**: `GET /api/pesanan/{id}/cetak-struk` atau `GET /api/public/pesanan/{id}/cetak-struk`
- **Akses**: Publik / Kasir / Admin / Super Admin
- **Response**: Stream file PDF (format kertas struk thermal 80mm roll, memuat rincian pesanan online, data pemesan, alamat/catatan, item belanja, dan footer toko)

---

### 5.11 Laporan & Analitik Dasbor

#### 1. Ringkasan Dasbor (Statistik Real-Time)
- **Endpoint**: `GET /api/laporan/dasbor`
- **Akses**: Admin, Super Admin / Pemilik
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Data ringkasan dasbor berhasil dimuat.",
    "data": {
      "hari_ini": {
        "tanggal": "2026-09-27",
        "total_omset": 159000,
        "total_transaksi": 2
      },
      "bulan_ini": {
        "bulan": "September 2026",
        "total_omset": 320000,
        "total_transaksi": 6,
        "total_keuntungan_margin": 38500
      },
      "inventaris": {
        "total_produk": 13,
        "total_stok_fisik": 156,
        "total_modal_barang": 485000,
        "total_stok_menipis": 13,
        "daftar_stok_menipis": [ ... ]
      }
    }
  }
  ```

#### 2. Laporan Penjualan (Filter Periode & Ekspor)
- **Endpoint**: `GET /api/laporan/penjualan`
- **Akses**: Admin, Super Admin
- **Query Params**:
  - `start_date` (YYYY-MM-DD, default: awal bulan ini)
  - `end_date` (YYYY-MM-DD, default: hari ini)
  - `user_id` (integer, opsional)

#### 3. Laporan Belanja / Pengadaan
- **Endpoint**: `GET /api/laporan/belanja`
- **Akses**: Admin, Super Admin
- **Query Params**: `?start_date=&end_date=&supplier_id=`

#### 4. Laporan Stok Menipis (Alert Inventaris)
- **Endpoint**: `GET /api/laporan/stok-menipis`
- **Akses**: Kasir, Admin, Super Admin

#### 5. Laporan Laba Bersih / Keuntungan (HPP vs Harga Jual)
- **Endpoint**: `GET /api/laporan/keuntungan`
- **Akses**: Super Admin
- **Query Params**: `?start_date=&end_date=`
- **Response (200 OK)**:
  ```json
  {
    "success": true,
    "data": {
      "periode": {
        "start_date": "2026-09-01",
        "end_date": "2026-09-27"
      },
      "total_omset": 68500000,
      "total_hpp_pokok": 54200000,
      "total_diskon_diberikan": 350000,
      "laba_kotor": 13950000
    }
  }
  ```

---

## 6. Contoh Implementasi Klien (Flutter / Dart)

Contoh service Flutter untuk mengintegrasikan autentikasi, transaksi POS, dan katalog pemesanan online:

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  static const String baseUrl = 'http://10.0.2.2:8000/api';
  String? _token;

  void setToken(String token) {
    _token = token;
  }

  Map<String, String> get _headers => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    if (_token != null) 'Authorization': 'Bearer $_token',
  };

  // 1. Login
  Future<Map<String, dynamic>> login(String username, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: _headers,
      body: jsonEncode({'username': username, 'password': password}),
    );
    final data = jsonDecode(response.body);
    if (response.statusCode == 200 && data['success'] == true) {
      setToken(data['data']['token']);
    }
    return data;
  }

  // 2. Cari Barang by Barcode (POS Scanner)
  Future<Map<String, dynamic>> scanBarcode(String barcode) async {
    final response = await http.get(
      Uri.parse('$baseUrl/barang/barcode/$barcode'),
      headers: _headers,
    );
    return jsonDecode(response.body);
  }

  // 3. Simpan Transaksi Penjualan (Kasir)
  Future<Map<String, dynamic>> submitPenjualan({
    required String metodePembayaran,
    required double bayar,
    required double diskon,
    required List<Map<String, dynamic>> items,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/penjualan'),
      headers: _headers,
      body: jsonEncode({
        'metode_pembayaran': metodePembayaran,
        'bayar': bayar,
        'diskon': diskon,
        'items': items,
      }),
    );
    return jsonDecode(response.body);
  }

  // 4. Kirim Pesanan Online (Katalog Publik / Customer)
  Future<Map<String, dynamic>> kirimPesananPublik({
    required String namaPemesan,
    required String noTelepon,
    required String alamat,
    String? catatan,
    required List<Map<String, dynamic>> items,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/public/pesanan'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'nama_pemesan': namaPemesan,
        'no_telepon': noTelepon,
        'alamat': alamat,
        'catatan': catatan,
        'items': items,
      }),
    );
    return jsonDecode(response.body);
  }
}
```

---

## 7. Changelog & Versi API

| Versi | Tanggal Rilis | Catatan Pembaruan |
| :---: | :---: | :--- |
| **v1.0.0** | 24 September 2026 | Inisialisasi Backend: Auth Sanctum, Master Barang, Kategori, Supplier, Belanja, Penjualan POS, dan Cetak Struk PDF. |
| **v1.1.0** | 25 September 2026 | Penambahan Hak Akses RBAC 3-Level (`super_admin`, `admin`, `kasir`), Laporan Laba/Rugi, dan Dashboard Analytics. |
| **v1.2.0** | 27 September 2026 | Penambahan Katalog Publik (`/public/produk`, `/public/cek-stok`, `/public/pesanan`) dan Modul Manajemen Pesanan Masuk Admin (`/pesanan`). |

---

## 8. Troubleshooting & Masalah Umum

1. **`Unauthenticated (401)` pada Postman / Mobile App**:
   - Pastikan header `Authorization: Bearer <token>` disertakan pada setiap request yang membutuhkan autentikasi.
   - Pastikan header `Accept: application/json` selalu dikirim agar Laravel merespon dalam format JSON, bukan redirect HTML.
2. **`Error 500: Database Connection Refused`**:
   - Pastikan MySQL/MariaDB aktif (misalnya melalui Laragon / XAMPP).
   - Periksa konfigurasi kredensial pada file `.env` server (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
3. **Gambar Produk / Logo Tidak Muncul (404 Not Found)**:
   - Jalankan perintah symlink storage di terminal root server:
     ```bash
     php artisan storage:link
     ```
4. **CORS Error pada Akses Browser / Web Frontend**:
   - Pastikan konfigurasi `config/cors.php` telah menyertakan origin klien atau diatur `allowed_origins => ['*']` untuk tahap pengembangan.
5. **Pemesanan Gagal: Stok Kurang Saat Checkout**:
   - Pelanggan mencoba memesan jumlah yang melebihi ketersediaan `stok` barang di database.
   - Gunakan endpoint `POST /api/public/cek-stok` sebelum submit formulir pemesanan untuk validasi dini di aplikasi klien.
6. **Field Supplier Tidak Valid**:
   - Pastikan menggunakan nama field `no_telepon` (bukan `telepon`) saat menambah atau mengubah data supplier.
