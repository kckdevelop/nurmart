<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesananOnlineTest extends TestCase
{
    use RefreshDatabase;

    protected $userPemilik;
    protected $kategori;
    protected $barang;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPemilik = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'pemilik@test.com',
        ]);

        $this->kategori = Kategori::create([
            'nama_kategori' => 'Sembako',
        ]);

        $this->barang = Barang::create([
            'kode_sku' => 'SBK-001',
            'barcode' => '8999909101',
            'nama_barang' => 'Beras Pandan Wangi 5kg',
            'kategori_id' => $this->kategori->id,
            'harga_beli' => 60000,
            'harga_jual' => 70000,
            'stok' => 10,
            'satuan' => 'karung',
        ]);
    }

    public function test_halaman_pesan_publik_bisa_diakses(): void
    {
        $responseRoot = $this->get('/');
        $responseRoot->assertStatus(200);

        $response = $this->get('/pesan');
        $response->assertStatus(200);

        $responseOrder = $this->get('/order');
        $responseOrder->assertStatus(200);

        $responseAdmin = $this->get('/admin');
        $responseAdmin->assertStatus(200);
    }

    public function test_api_katalog_produk_publik_menghilangkan_produk_stok_habis(): void
    {
        // Buat produk dengan stok 0
        Barang::create([
            'kode_sku' => 'HABIS-001',
            'barcode' => '8999909999',
            'nama_barang' => 'Minyak Goreng Habis',
            'kategori_id' => $this->kategori->id,
            'harga_beli' => 15000,
            'harga_jual' => 18000,
            'stok' => 0,
            'satuan' => 'liter',
        ]);

        $response = $this->getJson('/api/public/produk');
        $response->assertStatus(200)
            ->assertJsonPath('status', true)
            ->assertJsonCount(1, 'data'); // Hanya 1 barang yang stoknya > 0 ($this->barang)

        $data = $response->json('data');
        $this->assertEquals('SBK-001', $data[0]['kode_sku']);
    }

    public function test_api_cek_stok_produk(): void
    {
        $response = $this->postJson('/api/public/cek-stok', [
            'barang_id' => $this->barang->id,
            'jumlah' => 5,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.is_sufficient', true);

        // Cek jika melebihi stok
        $responseExcess = $this->postJson('/api/public/cek-stok', [
            'barang_id' => $this->barang->id,
            'jumlah' => 15,
        ]);

        $responseExcess->assertStatus(200)
            ->assertJsonPath('data.is_sufficient', false);
    }

    public function test_pemesanan_berhasil_dan_otomatis_memotong_stok(): void
    {
        $this->assertEquals(10, $this->barang->fresh()->stok);

        $payload = [
            'nama_pemesan' => 'Budi Santoso',
            'no_telepon' => '081234567890',
            'alamat' => 'Jl. Mawar No. 12, Majalengka',
            'items' => [
                [
                    'barang_id' => $this->barang->id,
                    'jumlah' => 3,
                ],
            ],
        ];

        $response = $this->postJson('/api/public/pesanan', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.pesanan.nama_pemesan', 'Budi Santoso')
            ->assertJsonPath('data.pesanan.total_harga', 210000);

        // Pastikan stok berkurang dari 10 menjadi 7
        $this->assertEquals(7, $this->barang->fresh()->stok);
    }

    public function test_pemesanan_gagal_jika_stok_tidak_cukup(): void
    {
        $payload = [
            'nama_pemesan' => 'Andi',
            'items' => [
                [
                    'barang_id' => $this->barang->id,
                    'jumlah' => 15, // Stok hanya 10
                ],
            ],
        ];

        $response = $this->postJson('/api/public/pesanan', $payload);

        $response->assertStatus(422)
            ->assertJsonPath('status', false);

        // Stok tidak berubah
        $this->assertEquals(10, $this->barang->fresh()->stok);
    }

    public function test_admin_batalkan_pesanan_otomatis_mengembalikan_stok(): void
    {
        // 1. Pesan 4 barang lebih dulu
        $this->postJson('/api/public/pesanan', [
            'nama_pemesan' => 'Rina',
            'items' => [
                [
                    'barang_id' => $this->barang->id,
                    'jumlah' => 4,
                ],
            ],
        ]);

        $this->assertEquals(6, $this->barang->fresh()->stok);

        $pesanan = Pesanan::first();
        $this->assertNotNull($pesanan);

        // 2. Admin batalkan pesanan
        $token = $this->userPemilik->createToken('admin-test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson("/api/pesanan/{$pesanan->id}/status", [
                'status' => 'dibatalkan',
                'catatan_admin' => 'Pelanggan minta dibatalkan',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.status', 'dibatalkan');

        // 3. Pastikan stok kembali bertambah 4 menjadi 10!
        $this->assertEquals(10, $this->barang->fresh()->stok);
    }
}
