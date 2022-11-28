<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxes', function (Blueprint $table) {
            $table->integer('proforma_id')->unsigned()->nullable();
            $table->foreign('proforma_id')->references('id')->on('proformas')->onDelete('cascade');
            $table->integer('proforma_item_id')->unsigned()->nullable();
            $table->foreign('proforma_item_id')->references('id')->on('proforma_items')->onDelete('cascade');
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
            $table->dropColumn('proforma_id');
            $table->dropColumn('proforma_item_id');
        });
    }
}
