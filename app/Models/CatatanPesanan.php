<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanPesanan extends Model
{
    use HasFactory;

    protected $table = 'catatan_pesanans';

    protected $fillable = [
        'user_id',
        'judul',
        'marketplace',
        'nomor_resi',
        'nama_toko',
        'status',
        'tanggal_pesan',
        'estimasi_datang',
        'total_nilai',
        'catatan_teks',
    ];

    protected $casts = [
        'tanggal_pesan' => 'date:Y-m-d',
        'estimasi_datang' => 'date:Y-m-d',
        'total_nilai' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
