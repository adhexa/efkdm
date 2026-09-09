<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('evaluation_code')->unique(); // E.g. MONEV-2026-Q3-01
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Created by vendor_admin
            $table->string('title'); // E.g. Laporan Evaluasi Triwulan III Kinerja FKDM & Potensi Kerawanan Pemda
            $table->string('period_name'); // E.g. Triwulan III - 2026
            $table->string('target_region'); // E.g. Kabupaten / Kota Pemda
            $table->integer('total_target_reports')->default(50);
            $table->integer('total_realized_reports')->default(0);
            $table->decimal('compliance_rate', 5, 2)->default(0.00); // Percentage
            $table->decimal('risk_index_score', 5, 2)->default(0.00); // Indeks Kerawanan Wilayah (0-100)
            $table->text('executive_summary');
            $table->text('consultant_recommendations'); // Rekomendasi Konsultan Swasta untuk Pemda
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
