<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pemilik (default dev)
        $pemilik = User::firstOrCreate(
            ['email' => 'pemilik@nurmart.com'],
            [
                'name' => 'Haji Mansyur (Pemilik Toko)',
                'password' => Hash::make('password123'),
                'role' => 'pemilik',
            ]
        );

        // 2. Akun Pemilik Asli (Nurohman)
        User::updateOrCreate(
            ['email' => 'nurohman11@gmail.com'],
            [
                'name' => 'Nurohman',
                'password' => Hash::make('password123'),
                'role' => 'pemilik',
            ]
        );

        // 3. Akun Kasir
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@nurmart.com'],
            [
                'name' => 'Siti Aminah (Kasir)',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
            ]
        );

        // 3. Kategori
        $katSembako = Kategori::firstOrCreate(['nama_kategori' => 'Sembako']);
        $katMinuman = Kategori::firstOrCreate(['nama_kategori' => 'Minuman']);
        $katSnack   = Kategori::firstOrCreate(['nama_kategori' => 'Makanan Ringan']);
        $katSabun   = Kategori::firstOrCreate(['nama_kategori' => 'Kebutuhan Mandi & Cuci']);

        // 4. Supplier
        $sup1 = Supplier::firstOrCreate(
            ['nama_supplier' => 'PT Sumber Pangan Sejahtera'],
            [
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Pergudangan Indah No. 12, Jakarta Timur'
            ]
        );

        $sup2 = Supplier::firstOrCreate(
            ['nama_supplier' => 'CV Distribusi Berkah Jaya'],
            [
                'no_telepon' => '082198765432',
                'alamat' => 'Jl. Pasar Baru No. 45, Bekasi'
            ]
        );

        // 5. Barang / Produk
        Barang::firstOrCreate(
            ['kode_sku' => 'BRG-0001'],
            [
                'barcode' => '8992345100012',
                'nama_barang' => 'Beras Pandan Wangi 5kg',
                'kategori_id' => $katSembako->id,
                'harga_beli' => 68000,
                'harga_jual' => 76000,
                'stok' => 25,
                'satuan' => 'dus/karung',
                'gambar_url' => null,
            ]
        );

        Barang::firstOrCreate(
            ['kode_sku' => 'BRG-0002'],
            [
                'barcode' => '8999999555111',
                'nama_barang' => 'Minyak Goreng SunCo 2 Liter',
                'kategori_id' => $katSembako->id,
                'harga_beli' => 33000,
                'harga_jual' => 37500,
                'stok' => 40,
                'satuan' => 'pcs',
                'gambar_url' => null,
            ]
        );

        Barang::firstOrCreate(
            ['kode_sku' => 'BRG-0003'],
            [
                'barcode' => '8991102220033',
                'nama_barang' => 'Gula Pasir Gulaku 1kg',
                'kategori_id' => $katSembako->id,
                'harga_beli' => 15000,
                'harga_jual' => 17500,
                'stok' => 50,
                'satuan' => 'pcs',
                'gambar_url' => null,
            ]
        );

        Barang::firstOrCreate(
            ['kode_sku' => 'BRG-0004'],
            [
                'barcode' => '8998866200044',
                'nama_barang' => 'Teh Botol Sosro Kotak 250ml',
                'kategori_id' => $katMinuman->id,
                'harga_beli' => 3000,
                'harga_jual' => 4000,
                'stok' => 4, // Sengaja di bawah 10 untuk tes peringatan stok menipis
                'satuan' => 'pcs',
                'gambar_url' => null,
            ]
        );

        Barang::firstOrCreate(
            ['kode_sku' => 'BRG-0005'],
            [
                'barcode' => '8993456789012',
                'nama_barang' => 'Indomie Goreng Original 85g',
                'kategori_id' => $katSnack->id,
                'harga_beli' => 2800,
                'harga_jual' => 3500,
                'stok' => 120,
                'satuan' => 'pcs',
                'gambar_url' => null,
            ]
        );

        // 6. Contoh Catatan Pesanan Online Marketplace (Shopee, Tokopedia, dll)
        \App\Models\CatatanPesanan::firstOrCreate(
            ['judul' => 'Restok Beras & Minyak SunCo (Shopee Official Store)'],
            [
                'user_id' => $pemilik->id,
                'marketplace' => 'Shopee',
                'nomor_resi' => 'SPXID04829103847',
                'nama_toko' => 'Wings Official Shop / Distributor Sembako',
                'status' => 'dalam_perjalanan',
                'tanggal_pesan' => now()->subDays(2)->toDateString(),
                'estimasi_datang' => now()->addDays(1)->toDateString(),
                'total_nilai' => 850000,
                'catatan_teks' => '<h3><span style="color: #ea580c;"><strong>📦 Pesanan Shopee - Restok Toko</strong></span></h3>
<p>Pesanan telah dikirim oleh penjual via SPX Express. Mohon kasir cek paket saat kurir tiba.</p>
<table style="border-collapse: collapse; width: 100%;" border="1">
<thead>
<tr style="background-color: #f1f5f9;">
<th style="padding: 8px; text-align: left;">Nama Barang</th>
<th style="padding: 8px; text-align: center;">Jumlah</th>
<th style="padding: 8px; text-align: right;">Estimasi Harga</th>
</tr>
</thead>
<tbody>
<tr>
<td style="padding: 8px;">Minyak Goreng SunCo 2L (Karton)</td>
<td style="padding: 8px; text-align: center;">5 Karton (30 Pouch)</td>
<td style="padding: 8px; text-align: right;">Rp 510.000</td>
</tr>
<tr>
<td style="padding: 8px;">Gula Pasir Gulaku Premium 1kg</td>
<td style="padding: 8px; text-align: center;">20 Bungkus</td>
<td style="padding: 8px; text-align: right;">Rp 340.000</td>
</tr>
</tbody>
</table>
<p><br><strong>Catatan Tambahan:</strong></p>
<ul>
<li>Gunakan voucher gratis ongkir &amp; cashback koin.</li>
<li>Periksa segel kardus sebelum tanda tangan terima paket.</li>
</ul>',
            ]
        );

        \App\Models\CatatanPesanan::firstOrCreate(
            ['judul' => 'Sabun Mandi & Deterjen Bubuk (Tokopedia)'],
            [
                'user_id' => $pemilik->id,
                'marketplace' => 'Tokopedia',
                'nomor_resi' => 'TKP01-99882211',
                'nama_toko' => 'Unilever Wholesale Official',
                'status' => 'belum_datang',
                'tanggal_pesan' => now()->subDay()->toDateString(),
                'estimasi_datang' => now()->addDays(3)->toDateString(),
                'total_nilai' => 460000,
                'catatan_teks' => '<h3><span style="color: #16a34a;"><strong>📦 Pesanan Tokopedia - Kebutuhan Cuci &amp; Mandi</strong></span></h3>
<p>Status: Penjual sedang menyiapkan barang di gudang pusat.</p>
<ul>
<li>Lifebuoy Sabun Cair 450ml (12 pcs)</li>
<li>Rinso Molto Deterjen 770g (10 pcs)</li>
<li>Sunlight Jeruk Nipis 700ml (1 Dus)</li>
</ul>
<p><em>Harap langsung masukkan ke rak display setelah dibongkar.</em></p>',
            ]
        );
    }
}
