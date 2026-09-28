<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatatanPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatatanPesananController extends Controller
{
    /**
     * Tampilkan daftar semua catatan pesanan online marketplace.
     */
    public function index(Request $request)
    {
        $query = CatatanPesanan::with('user:id,name,role')->latest();

        // Filter status jika ada
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // Filter marketplace jika ada
        if ($request->filled('marketplace') && $request->marketplace !== 'semua') {
            $query->where('marketplace', $request->marketplace);
        }

        // Pencarian teks (judul, resi, nama_toko, isi catatan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('nomor_resi', 'like', "%{$search}%")
                  ->orWhere('nama_toko', 'like', "%{$search}%")
                  ->orWhere('catatan_teks', 'like', "%{$search}%");
            });
        }

        $catatan = $query->get();

        // Ringkasan statistik
        $stats = [
            'total' => CatatanPesanan::count(),
            'belum_datang' => CatatanPesanan::where('status', 'belum_datang')->count(),
            'dalam_perjalanan' => CatatanPesanan::where('status', 'dalam_perjalanan')->count(),
            'sebagian_datang' => CatatanPesanan::where('status', 'sebagian_datang')->count(),
            'selesai' => CatatanPesanan::where('status', 'selesai')->count(),
        ];

        return response()->json([
            'status' => true,
            'message' => 'Data catatan pesanan online berhasil dimuat.',
            'stats' => $stats,
            'data' => $catatan
        ]);
    }

    /**
     * Simpan catatan pesanan online baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'marketplace' => 'nullable|string|max:100',
            'nomor_resi' => 'nullable|string|max:100',
            'nama_toko' => 'nullable|string|max:255',
            'status' => 'nullable|in:belum_datang,dalam_perjalanan,sebagian_datang,selesai,dibatalkan',
            'tanggal_pesan' => 'nullable|date',
            'estimasi_datang' => 'nullable|date',
            'total_nilai' => 'nullable|numeric|min:0',
            'catatan_teks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user() ? $request->user()->id : null;
        if (empty($data['marketplace'])) {
            $data['marketplace'] = 'Shopee';
        }
        if (empty($data['status'])) {
            $data['status'] = 'belum_datang';
        }
        if (empty($data['tanggal_pesan'])) {
            $data['tanggal_pesan'] = now()->toDateString();
        }

        $catatan = CatatanPesanan::create($data);
        $catatan->load('user:id,name,role');

        return response()->json([
            'status' => true,
            'message' => 'Catatan pesanan online berhasil disimpan.',
            'data' => $catatan
        ], 201);
    }

    /**
     * Tampilkan detail satu catatan pesanan.
     */
    public function show($id)
    {
        $catatan = CatatanPesanan::with('user:id,name,role')->find($id);

        if (!$catatan) {
            return response()->json([
                'status' => false,
                'message' => 'Catatan pesanan tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $catatan
        ]);
    }

    /**
     * Perbarui catatan pesanan online.
     */
    public function update(Request $request, $id)
    {
        $catatan = CatatanPesanan::find($id);

        if (!$catatan) {
            return response()->json([
                'status' => false,
                'message' => 'Catatan pesanan tidak ditemukan.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'sometimes|required|string|max:255',
            'marketplace' => 'nullable|string|max:100',
            'nomor_resi' => 'nullable|string|max:100',
            'nama_toko' => 'nullable|string|max:255',
            'status' => 'nullable|in:belum_datang,dalam_perjalanan,sebagian_datang,selesai,dibatalkan',
            'tanggal_pesan' => 'nullable|date',
            'estimasi_datang' => 'nullable|date',
            'total_nilai' => 'nullable|numeric|min:0',
            'catatan_teks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $catatan->update($validator->validated());
        $catatan->load('user:id,name,role');

        return response()->json([
            'status' => true,
            'message' => 'Catatan pesanan berhasil diperbarui.',
            'data' => $catatan
        ]);
    }

    /**
     * Hapus catatan pesanan online.
     */
    public function destroy($id)
    {
        $catatan = CatatanPesanan::find($id);

        if (!$catatan) {
            return response()->json([
                'status' => false,
                'message' => 'Catatan pesanan tidak ditemukan.'
            ], 404);
        }

        $catatan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Catatan pesanan berhasil dihapus.'
        ]);
    }
}
