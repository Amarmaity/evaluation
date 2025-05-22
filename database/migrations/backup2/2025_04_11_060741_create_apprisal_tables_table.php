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
        Schema::create('apprisal_tables', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id')->nullable();
            $table->string('company_percentage')->nullable();
            $table->string('financial_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apprisal_tables');
    }
};
