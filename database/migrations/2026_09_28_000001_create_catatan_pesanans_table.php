<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catatan_pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('judul');
            $table->string('marketplace')->default('Shopee'); // Shopee, Tokopedia, TikTok Shop, Lazada, Blibli, Lainnya
            $table->string('nomor_resi')->nullable();
            $table->string('nama_toko')->nullable();
            $table->enum('status', ['belum_datang', 'dalam_perjalanan', 'sebagian_datang', 'selesai', 'dibatalkan'])->default('belum_datang');
            $table->date('tanggal_pesan')->nullable();
            $table->date('estimasi_datang')->nullable();
            $table->decimal('total_nilai', 14, 2)->default(0);
            $table->longText('catatan_teks')->nullable(); // HTML dari TinyMCE
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_pesanans');
    }
};
