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
        Schema::rename('clinical_histories', 'medical_records');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('medical_records', 'clinical_histories');
    }
};
