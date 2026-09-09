<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('sk_number')->nullable()->after('institution_name');
            $table->string('sk_document_path')->nullable()->after('sk_number');
            $table->string('position_title')->nullable()->after('sk_document_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sk_number', 'sk_document_path', 'position_title']);
        });
    }
};
