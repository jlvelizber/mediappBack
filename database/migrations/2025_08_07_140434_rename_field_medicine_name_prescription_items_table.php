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
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->renameColumn('medicine_name', 'medication_name');
            $table->renameColumn('instructions', 'notes');
            // change type dosage from integer to string
            $table->string('dosage')->change();
            // change type duration from date to string
            $table->string('duration')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->renameColumn('medication_name', 'medicine_name');
            $table->renameColumn('notes', 'instructions');
            // revert type dosage from string to integer
            $table->integer('dosage')->change();
            // revert type duration from string to date
            $table->date('duration')->change();
        });
    }
};
