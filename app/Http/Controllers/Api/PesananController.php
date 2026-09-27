<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPesanan;
use App\Models\Pengaturan;
use App\Models\Pesanan;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    use ApiResponseTrait;

    // =========================================================================
    // 1. PUBLIC ENDPOINTS (Untuk Halaman Pelanggan / Umum)
    // =========================================================================

    /**
     * Mengambil daftar produk yang tersedia untuk dipesan pelanggan.
     * GET /api/public/produk
     */
    public function getProdukKatalog(Request $request): JsonResponse
    {
        $query = Barang::with('kategori');

        // Hanya produk dengan stok > 0 kecuali jika diminta semua
        if (!$request->boolean('include_out_of_stock', false)) {
            $query->where('stok', '>', 0);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'nama_barang');
        $sortOrder = $request->get('sort_order', 'asc');

        if ($sortBy === 'harga_termurah') {
            $query->orderBy('harga_jual', 'asc');
        } elseif ($sortBy === 'harga_termahal') {
            $query->orderBy('harga_jual', 'desc');
        } elseif ($sortBy === 'stok_terbanyak') {
            $query->orderBy('stok', 'desc');
        } else {
            $query->orderBy('nama_barang', $sortOrder === 'desc' ? 'desc' : 'asc');
        }

        if ($request->boolean('all', true)) {
            $produk = $query->get();
            return $this->successResponse($produk, 'Daftar produk tersedia berhasil diambil.');
        }

        $perPage = (int) $request->get('per_page', 20);
        $produk = $query->paginate($perPage);

        return $this->successResponse($produk, 'Daftar produk tersedia berhasil diambil.');
    }

    /**
     * Memeriksa stok produk secara realtime sebelum tambah/kurangi kuantiti pesanan.
     * POST /api/public/cek-stok
     */
    public function cekStok(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $barang = Barang::find($request->barang_id);
        if (!$barang) {
            return $this->errorResponse('Barang tidak ditemukan.', 404);
        }

        $requestedJumlah = (int) $request->get('jumlah', 1);
        $isSufficient = $barang->stok >= $requestedJumlah;

        return $this->successResponse([
            'barang_id' => $barang->id,
            'nama_barang' => $barang->nama_barang,
            'stok_tersedia' => $barang->stok,
            'jumlah_diminta' => $requestedJumlah,
            'is_sufficient' => $isSufficient,
            'max_allowed' => $barang->stok,
        ], $isSufficient ? 'Stok tersedia.' : "Stok tidak mencukupi (sisa: {$barang->stok}).");
    }

    /**
     * Mengirim pesanan baru dari pelanggan umum.
     * Stok produk otomatis berkurang secara atomik dalam database transaction.
     * POST /api/public/pesanan
     */
    public function storePesanan(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_pemesan' => 'required|string|max:150',
            'no_telepon' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|integer|exists:barang,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ], [
            'nama_pemesan.required' => 'Nama pemesan wajib diisi.',
            'items.required' => 'Keranjang pesanan tidak boleh kosong.',
            'items.min' => 'Pilih minimal 1 produk untuk memesan.',
            'items.*.barang_id.required' => 'ID Produk tidak valid.',
            'items.*.jumlah.min' => 'Jumlah pesanan minimal 1.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $validated = $validator->validated();

        try {
            $pesanan = DB::transaction(function () use ($validated) {
                $totalHarga = 0;
                $processedItems = [];

                // 1. Verifikasi ketersediaan stok & kunci baris (lockForUpdate)
                foreach ($validated['items'] as $item) {
                    $barang = Barang::lockForUpdate()->find($item['barang_id']);

                    if (!$barang) {
                        throw new Exception("Produk dengan ID {$item['barang_id']} tidak ditemukan.");
                    }

                    if ($barang->stok < $item['jumlah']) {
                        throw new Exception("Stok '{$barang->nama_barang}' tidak mencukupi. Sisa stok yang tersedia: {$barang->stok}, dipesan: {$item['jumlah']}. Silakan sesuaikan jumlah pesanan.");
                    }

                    $hargaSatuan = $barang->harga_jual;
                    $subtotal = $item['jumlah'] * $hargaSatuan;
                    $totalHarga += $subtotal;

                    $processedItems[] = [
                        'barang' => $barang,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $hargaSatuan,
                        'subtotal' => $subtotal,
                    ];
                }

                // 2. Generate nomor pesanan unik
                $noPesanan = Pesanan::generateNoPesanan();

                // 3. Simpan data header Pesanan
                $pesanan = Pesanan::create([
                    'no_pesanan' => $noPesanan,
                    'tanggal' => Carbon::now(),
                    'nama_pemesan' => trim($validated['nama_pemesan']),
                    'no_telepon' => $validated['no_telepon'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'catatan' => $validated['catatan'] ?? null,
                    'total_harga' => $totalHarga,
                    'status' => 'menunggu',
                ]);

                // 4. Simpan detail pesanan dan otomatis kurangi stok barang
                foreach ($processedItems as $pItem) {
                    DetailPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'barang_id' => $pItem['barang']->id,
                        'jumlah' => $pItem['jumlah'],
                        'harga_satuan' => $pItem['harga_satuan'],
                        'subtotal' => $pItem['subtotal'],
                    ]);

                    // Kurangi stok barang
                    $pItem['barang']->decrement('stok', $pItem['jumlah']);
                }

                return $pesanan;
            });

            $pesanan->load('details.barang');
            $pengaturan = Pengaturan::getUtama();

            return $this->successResponse([
                'pesanan' => $pesanan,
                'kontak_toko' => [
                    'nama_toko' => $pengaturan->nama_toko,
                    'telepon' => $pengaturan->telepon,
                    'alamat' => $pengaturan->alamat,
                ],
            ], 'Pesanan Anda berhasil dikirim dan stok produk telah diamankan!', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    // =========================================================================
    // 2. ADMIN ENDPOINTS (Untuk Manajemen Pesanan & Cek Pesanan)
    // =========================================================================

    /**
     * Mendapatkan daftar pesanan masuk untuk admin.
     * GET /api/pesanan
     */
    public function index(Request $request): JsonResponse
    {
        $query = Pesanan::with(['details.barang.kategori']);

        // Filter status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // Filter rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        // Filter pencarian nama pemesan / no pesanan / no telepon
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('no_pesanan', 'like', "%{$keyword}%")
                  ->orWhere('nama_pemesan', 'like', "%{$keyword}%")
                  ->orWhere('no_telepon', 'like', "%{$keyword}%");
            });
        }

        // Hitung ringkasan status
        $counts = [
            'total' => Pesanan::count(),
            'menunggu' => Pesanan::where('status', 'menunggu')->count(),
            'diproses' => Pesanan::where('status', 'diproses')->count(),
            'selesai' => Pesanan::where('status', 'selesai')->count(),
            'dibatalkan' => Pesanan::where('status', 'dibatalkan')->count(),
        ];

        $perPage = (int) $request->get('per_page', 15);
        $pesanans = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return $this->successResponse([
            'summary' => $counts,
            'pesanan' => $pesanans,
        ], 'Daftar pesanan berhasil diambil.');
    }

    /**
     * Detail lengkap satu pesanan.
     * GET /api/pesanan/{id}
     */
    public function show(int $id): JsonResponse
    {
        $pesanan = Pesanan::with(['details.barang.kategori'])->find($id);

        if (!$pesanan) {
            return $this->errorResponse('Pesanan tidak ditemukan.', 404);
        }

        return $this->successResponse($pesanan, 'Detail pesanan berhasil diambil.');
    }

    /**
     * Mengubah status pesanan (Konfirmasi / Proses / Selesai / Batalkan).
     * Ketika status diubah ke 'dibatalkan', STOK BARANG OTOMATIS DIKEMBALIKAN.
     * PUT /api/pesanan/{id}/status
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:menunggu,diproses,selesai,dibatalkan',
            'catatan_admin' => 'nullable|string|max:1000',
        ], [
            'status.required' => 'Status pesanan wajib dipilih.',
            'status.in' => 'Status yang valid: menunggu, diproses, selesai, dibatalkan.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $newStatus = $request->status;
        $catatanAdmin = $request->catatan_admin;

        try {
            $pesanan = DB::transaction(function () use ($id, $newStatus, $catatanAdmin) {
                $pesanan = Pesanan::with('details.barang')->lockForUpdate()->find($id);

                if (!$pesanan) {
                    throw new Exception('Pesanan tidak ditemukan.');
                }

                $oldStatus = $pesanan->status;

                // 1. JIKA STATUS BERUBAH MENJADI 'dibatalkan' (dan sebelumnya bukan 'dibatalkan')
                // -> Kembalikan stok semua barang dalam pesanan secara otomatis!
                if ($newStatus === 'dibatalkan' && $oldStatus !== 'dibatalkan') {
                    foreach ($pesanan->details as $detail) {
                        if ($detail->barang) {
                            $detail->barang->increment('stok', $detail->jumlah);
                        }
                    }
                }

                // 2. JIKA STATUS SEBELUMNYA 'dibatalkan' DAN DIUBAH KE STATUS LAIN (Diaktifkan kembali)
                // -> Cek ketersediaan stok kembali lalu kurangi stok
                if ($oldStatus === 'dibatalkan' && $newStatus !== 'dibatalkan') {
                    foreach ($pesanan->details as $detail) {
                        $barang = Barang::lockForUpdate()->find($detail->barang_id);
                        if (!$barang || $barang->stok < $detail->jumlah) {
                            $nama = $barang ? $barang->nama_barang : 'Produk';
                            $stokSisa = $barang ? $barang->stok : 0;
                            throw new Exception("Gagal mengaktifkan kembali pesanan. Stok '{$nama}' tidak mencukupi (sisa: {$stokSisa}, dibutuhkan: {$detail->jumlah}).");
                        }
                    }

                    // Kurangi kembali stok
                    foreach ($pesanan->details as $detail) {
                        $barang = Barang::find($detail->barang_id);
                        if ($barang) {
                            $barang->decrement('stok', $detail->jumlah);
                        }
                    }
                }

                // Simpan status baru
                $pesanan->status = $newStatus;
                if ($catatanAdmin !== null) {
                    $pesanan->catatan_admin = $catatanAdmin;
                }
                $pesanan->save();

                return $pesanan;
            });

            $pesanan->load(['details.barang.kategori']);

            $statusText = match ($newStatus) {
                'diproses' => 'diproses',
                'selesai' => 'diselesaikan',
                'dibatalkan' => 'dibatalkan dan stok barang telah dikembalikan',
                default => 'diperbarui',
            };

            return $this->successResponse($pesanan, "Pesanan #{$pesanan->no_pesanan} berhasil {$statusText}.");
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
     * Menghapus pesanan.
     * DELETE /api/pesanan/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            DB::transaction(function () use ($id) {
                $pesanan = Pesanan::with('details.barang')->lockForUpdate()->find($id);

                if (!$pesanan) {
                    throw new Exception('Pesanan tidak ditemukan.');
                }

                // Jika pesanan belum dibatalkan, kembalikan stok sebelum dihapus
                if ($pesanan->status !== 'dibatalkan') {
                    foreach ($pesanan->details as $detail) {
                        if ($detail->barang) {
                            $detail->barang->increment('stok', $detail->jumlah);
                        }
                    }
                }

                $pesanan->delete();
            });

            return $this->successResponse(null, 'Pesanan berhasil dihapus dan stok telah disesuaikan.');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
