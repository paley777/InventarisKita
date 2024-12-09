<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama inventaris (misal: Laptop, Meja, dll)
            $table->enum('type', ['Elektronik', 'Non-Elektronik']); // Tipe inventaris (Elektronik atau Non-Elektronik)
            $table->string('ownership'); // Kepemilikan (Milik Sekolah, Pinjam, dll)
            $table->string('specification'); // Spesifikasi item
            $table->year('acquisition_year'); // Tahun pengadaan
            $table->integer('quantity')->default(1); // Jumlah inventaris
            $table->integer('layak_count')->default(0); // Jumlah unit yang layak
            $table->integer('tidak_layak_count')->default(0); // Jumlah unit yang tidak layak
            $table->foreignId('room_id')->constrained()->onDelete('cascade'); // Relasi dengan Ruangan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
