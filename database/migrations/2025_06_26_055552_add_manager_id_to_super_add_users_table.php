<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('super_add_users', function (Blueprint $table) {
            //
             $table->unsignedBigInteger('manager_id')->nullable()->after('employee_id');
        $table->foreign('manager_id')->references('id')->on('super_add_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('super_add_users', function (Blueprint $table) {
            //
        });
    }
};
