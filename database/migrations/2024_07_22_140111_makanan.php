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
        Schema::create('makanan', function(Blueprint $table) {
            $table->id();
            $table->string('nama_makanan');
            $table->text('deskripsi');
            $table->float('harga');
            $table->string('kategori');
            $table->string('gambar');
            $table->enum('status', ['tersedia','habis']);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
