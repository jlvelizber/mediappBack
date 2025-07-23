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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')
                ->constrained('prescriptions')
                ->onDelete('cascade');
            $table->string('medicine_name')->comment('Name of the medicine');
            $table->integer('dosage')->comment('Dosage of the medicine in mg');
            $table->string('frequency')->comment('Frequency of intake (e.g., daily, twice a day)');
            $table->text('instructions')->nullable()->comment('Additional instructions for the medicine');
            $table->date('duration')->comment('Start date for the prescription')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
