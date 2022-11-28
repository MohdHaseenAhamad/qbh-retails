<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIntoItemsClientsAndSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->nullable();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->nullable();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
}
