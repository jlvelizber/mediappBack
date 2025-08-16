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
        Schema::table('doctor_configurations', function (Blueprint $table) {
            $table->string('medical_center_name')->nullable();
            $table->string('medical_center_address')->nullable();
            $table->string('medical_center_phone')->nullable();
            $table->string('medical_center_email')->nullable();
            $table->string('medical_center_logo')->nullable()->comment('URL to the medical center logo');
            $table->string('medical_center_website')->nullable()->comment('URL to the medical center website');
            $table->string('medical_center_social_media')->nullable()->comment('Social media links for the medical center');
            $table->string('medical_center_tax_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_configurations', function (Blueprint $table) {
            $table->dropColumn([
                'medical_center_name',
                'medical_center_address',
                'medical_center_phone',
                'medical_center_email',
                'medical_center_logo',
                'medical_center_website',
                'medical_center_social_media',
                'medical_center_tax_id'
            ]);
        });
    }
};
