<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchaseIdIntoTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *  
     * @return void
     */
    public function up()
    {
        Schema::table('taxes', function (Blueprint $table) {
            $table->unsignedInteger('purchase_id')->nullable();
            // $table->foreign('purchase_id')->references('id')->on('purchases');

            $table->unsignedInteger('purchase_item_id')->nullable();
            // $table->foreign('purchase_item_id')->references('id')->on('purchase_items');
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
            // $table->dropForeign(['purchase_id']);
            $table->dropColumn('purchase_id');
            // $table->dropForeign(['purchase_item_id']);
            $table->dropColumn('purchase_item_id');
        });
    }
}