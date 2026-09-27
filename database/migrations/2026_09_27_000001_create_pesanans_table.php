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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('no_pesanan', 100)->unique();
            $table->dateTime('tanggal')->index();
            $table->string('nama_pemesan', 150)->index();
            $table->string('no_telepon', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('total_harga', 14, 2)->default(0);
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'dibatalkan'])->default('menunggu')->index();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang')->restrictOnDelete();
            $table->integer('jumlah')->unsigned();
            $table->decimal('harga_satuan', 12, 2)->unsigned();
            $table->decimal('subtotal', 14, 2)->unsigned();
            $table->timestamps();

            $table->index(['pesanan_id', 'barang_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
    }
};
