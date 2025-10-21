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
        Schema::table('appointments', function (Blueprint $table) {
            // Índice compuesto para el query findFutureAppointments
            // Optimiza: doctor_id + date_time + status
            $table->index(['doctor_id', 'date_time', 'status'], 'idx_appointments_doctor_datetime_status');
            
            // Índice para consultas por fecha específica
            $table->index(['doctor_id', 'date_time'], 'idx_appointments_doctor_datetime');
            
            // Índice para consultas por status
            $table->index(['status', 'date_time'], 'idx_appointments_status_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('idx_appointments_doctor_datetime_status');
            $table->dropIndex('idx_appointments_doctor_datetime');
            $table->dropIndex('idx_appointments_status_datetime');
        });
    }
};