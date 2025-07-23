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
        Schema::create('all_clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_name', 50)->nullable();
            $table->string('company_name', 50 )->nullable();
            $table->string('client_mobno', 20)->nullable();
            $table->string('client_email', 50)->nullable();
            $table->string('password');
            $table->boolean('status')->default(1);
            $table->string('user_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all_clients');
    }
};
