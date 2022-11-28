<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditIdIntoTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxes', function (Blueprint $table) {
            $table->unsignedInteger('credit_id')->nullable();
            $table->foreign('credit_id')->references('id')->on('credits');

            $table->unsignedInteger('credit_item_id')->nullable();
            $table->foreign('credit_item_id')->references('id')->on('credit_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxes', function (Blueprint $table) {
            $table->dropForeign(['credit_id']);
            $table->dropForeign(['credit_item_id']);
            $table->dropColumn('credit_id');
            $table->dropColumn('credit_item_id');
        });
    }
}
