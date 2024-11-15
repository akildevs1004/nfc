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
        Schema::create('nfc_tags', function (Blueprint $table) {
            $table->id();
            $table->integer("company_id");
            $table->integer("branch_id")->nullable();
            $table->integer("live_status")->default(0);
            $table->string("name");
            $table->string("serial_number");
            $table->string("location")->nullable();
            $table->dateTime("created_datetime")->nullable();
            $table->dateTime("updated_datetime")->nullable();
            $table->string("model_number")->nullable();
            $table->string("utc_time_zone")->nullable();
            $table->dateTime("last_live_datetime")->nullable();



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
        Schema::dropIfExists('nfc_tags');
    }
};
