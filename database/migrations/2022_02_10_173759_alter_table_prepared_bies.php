<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePreparedBies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prepared_bies', function (Blueprint $table) {
            $table->string('designation')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prepared_bies', function (Blueprint $table) {
            $table->dropColumn('designation');
            $table->dropColumn('contact_number');
            $table->dropColumn('email');
        });
    }
}
