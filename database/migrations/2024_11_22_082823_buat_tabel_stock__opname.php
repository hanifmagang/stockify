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
        Schema::create('stockOpname', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // relasi ke tabel products
            $table->unsignedBigInteger('category_id');
            $table->integer('masuk')->default(0); // jumlah barang masuk
            $table->integer('keluar')->default(0); // jumlah barang keluar
            $table->integer('stock_akhir')->default(0); // stock akhir
            $table->date('date'); // tanggal
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockOpname');
    }
};
