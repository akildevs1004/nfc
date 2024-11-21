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
        Schema::create('route_locations', function (Blueprint $table) {
            $table->id();
            $table->integer("company_id");
            $table->integer("branch_id")->nullable();
            $table->integer("customer_id")->nullable();
            $table->integer("route_id");
            $table->integer("device_id");
            $table->time("schedule_time");
            $table->string("notes")->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_locations');
    }
};
