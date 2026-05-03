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
        Schema::table('behavior_records', function (Blueprint $table) {
            $table->foreignId('vitamin_type_id')->nullable()->after('violation_type_id')->constrained('vitamin_types')->nullOnDelete();
            // Make violation_type_id nullable (a record is either a violation OR a vitamin)
            $table->foreignId('violation_type_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('behavior_records', function (Blueprint $table) {
            $table->dropForeign(['vitamin_type_id']);
            $table->dropColumn('vitamin_type_id');
        });
    }
};
