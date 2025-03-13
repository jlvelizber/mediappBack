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
        Schema::create('doctor_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->string('default_appointment_duration')->nullable()->default(20)->comment('In minutes');
            $table->string('default_appointment_price')->nullable()->default(0);
            $table->string('default_appointment_currency')->nullable()->default('USD');
            $table->string('default_appointment_currency_symbol')->nullable()->default('$');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_configurations');
    }
};
