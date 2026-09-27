<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('pesanan:sync-sales', function () {
    $completed = \App\Models\Pesanan::with('details')->where('status', 'selesai')->whereNull('penjualan_id')->get();
    $synced = 0;

    foreach ($completed as $pesanan) {
        $todayCode = \Carbon\Carbon::parse($pesanan->tanggal ?? $pesanan->created_at)->format('Ymd');
        $countToday = \App\Models\Penjualan::whereDate('created_at', $pesanan->created_at)->count() + 1;
        $noNota = 'PJ-' . $todayCode . '-' . str_pad((string)$countToday, 4, '0', STR_PAD_LEFT);
        $kasirId = \App\Models\User::where('role', 'pemilik')->value('id') ?? \App\Models\User::first()?->id ?? 1;

        $penjualan = \App\Models\Penjualan::create([
            'no_nota' => $noNota,
            'tanggal' => $pesanan->tanggal ?? $pesanan->created_at ?? \Carbon\Carbon::now(),
            'kasir_id' => $kasirId,
            'total_belanja' => $pesanan->total_harga,
            'jumlah_bayar' => $pesanan->total_harga,
            'kembalian' => 0,
            'metode_pembayaran' => 'qris',
        ]);

        foreach ($pesanan->details as $detail) {
            \App\Models\DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'barang_id' => $detail->barang_id,
                'jumlah' => $detail->jumlah,
                'harga_jual_satuan' => $detail->harga_satuan,
                'subtotal' => $detail->subtotal,
            ]);
        }

        $pesanan->penjualan_id = $penjualan->id;
        $pesanan->save();
        $synced++;
    }

    $this->info("Berhasil sinkronisasi {$synced} pesanan online selesai ke transaksi Penjualan POS.");
})->purpose('Sinkronisasi pesanan online selesai ke transaksi Penjualan POS');
