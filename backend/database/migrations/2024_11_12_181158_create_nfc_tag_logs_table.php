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
        Schema::create('nfc_tag_logs', function (Blueprint $table) {
            $table->id();

            $table->integer("company_id");
            $table->integer("branch_id")->nullable();
            $table->string("serial_number");
            $table->integer("employee_id");
            $table->datetime("log_time");
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
        Schema::dropIfExists('nfc_tag_logs');
    }
};
