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
        Schema::table('tickets', function (Blueprint $table) {
            $table->boolean("is_technician_read")->default(false);
            $table->boolean("is_security_read")->default(false);
            $table->boolean("is_customer_read")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn("is_technician_read");
            $table->dropColumn("is_security_read");
            $table->dropColumn("is_customer_read");
        });
    }
};
