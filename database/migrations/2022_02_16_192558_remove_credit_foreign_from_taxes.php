<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCreditForeignFromTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // REMOVE FOREIGN
        // if (Schema::hasColumn('taxes', 'credit_id'))
        // {
        //     Schema::table('taxes', function (Blueprint $table) {
        //         $table->dropForeign(['credit_id']);
        //     });
        // }
        // if (Schema::hasColumn('taxes', 'credit_item_id'))
        // {
        //     Schema::table('taxes', function (Blueprint $table) {
        //         $table->dropForeign(['credit_item_id']);
        //     });
        // }

        // ADD FOREIGN
        // Schema::table('taxes', function (Blueprint $table) {
        //     // $table->unsignedInteger('credit_id')->nullable();
        //     $table->foreign('credit_id')->references('id')->on('credits');

        //     // $table->unsignedInteger('credit_item_id')->nullable();
        //     $table->foreign('credit_item_id')->references('id')->on('credit_items');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
