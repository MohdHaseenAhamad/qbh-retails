<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableEstimates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->string('prepared_by_id')->nullable();
            $table->string('deduction_per_item')->nullable();
             $table->string('upper_margin')->nullable()->default('3.5');
            $table->string('lower_margin')->nullable()->default('1.5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn('prepared_by_id');
            $table->dropColumn('deduction_per_item');
            $table->dropColumn('upper_margin');
            $table->dropColumn('lower_margin');
        });
    }
}
