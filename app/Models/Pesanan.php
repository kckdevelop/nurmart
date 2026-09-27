<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'no_pesanan',
        'tanggal',
        'nama_pemesan',
        'no_telepon',
        'alamat',
        'catatan',
        'total_harga',
        'status',
        'catatan_admin',
        'penjualan_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
            'total_harga' => 'float',
        ];
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    public function detailPesanans(): HasMany
    {
        return $this->details();
    }

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function scopeMenunggu(Builder $query): Builder
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDiproses(Builder $query): Builder
    {
        return $query->where('status', 'diproses');
    }

    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', 'selesai');
    }

    public function scopeDibatalkan(Builder $query): Builder
    {
        return $query->where('status', 'dibatalkan');
    }

    /**
     * Generate unique order number: ORD-YYYYMMDD-XXXX
     */
    public static function generateNoPesanan(): string
    {
        $todayCode = Carbon::now()->format('Ymd');
        $countToday = self::whereDate('created_at', Carbon::today())->count() + 1;
        
        do {
            $noPesanan = 'ORD-' . $todayCode . '-' . str_pad((string)$countToday, 4, '0', STR_PAD_LEFT);
            $exists = self::where('no_pesanan', $noPesanan)->exists();
            if ($exists) {
                $countToday++;
            }
        } while ($exists);

        return $noPesanan;
    }
}
