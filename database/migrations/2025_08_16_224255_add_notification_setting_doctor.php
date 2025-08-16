<?php

use App\Enum\WayNotificationEnum;
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
            $table->enum('notification_way', array_column(WayNotificationEnum::cases(), 'value'))
                ->default(WayNotificationEnum::BOTH->value)
                ->after('medical_center_tax_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_configurations', function (Blueprint $table) {
            $table->dropColumn('notification_way');
        });
    }
};
