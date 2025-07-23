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
        Schema::table('super_add_users', function (Blueprint $table) {
            //
             $table->unsignedBigInteger('manager_id')->nullable()->after('employee_id');
        $table->foreign('manager_id')->references('id')->on('super_add_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('super_add_users', function (Blueprint $table) {
            //
        });
    }
};
