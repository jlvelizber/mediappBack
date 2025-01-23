<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\UserRoleEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("users", function(Blueprint $table){
            $table->string("lastname");
            $table->enum("role", array_column(UserRoleEnum::cases(), 'value') )->default(UserRoleEnum::DOCTOR->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function(Blueprint $table){
            $table->dropColumn(["lastname", "role"]);
        });
    }
};
