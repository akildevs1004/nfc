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
        Schema::table('routes', function (Blueprint $table) {
            $table->boolean("sun")->default(false);
            $table->boolean("mon")->default(false);
            $table->boolean("tue")->default(false);
            $table->boolean("wed")->default(false);
            $table->boolean("thu")->default(false);
            $table->boolean("fri")->default(false);
            $table->boolean("sat")->default(false);

            $table->integer("repeat_minutes")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn("sun");
            $table->dropColumn("mon");
            $table->dropColumn("tue");
            $table->dropColumn("wed");
            $table->dropColumn("thu");
            $table->dropColumn("fri");
            $table->dropColumn("sat");
            $table->dropColumn("repeat_minutes");
        });
    }
};
