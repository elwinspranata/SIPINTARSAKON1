<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->string('tingkat', 10)->default('X')->after('id');
        });

        // Update existing data: parse tingkat from name prefix
        DB::table('classes')->get()->each(function ($class) {
            $tingkat = 'X';
            if (str_starts_with($class->name, 'XII')) {
                $tingkat = 'XII';
            } elseif (str_starts_with($class->name, 'XI')) {
                $tingkat = 'XI';
            } elseif (str_starts_with($class->name, 'X')) {
                $tingkat = 'X';
            }
            DB::table('classes')->where('id', $class->id)->update(['tingkat' => $tingkat]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('tingkat');
        });
    }
};
