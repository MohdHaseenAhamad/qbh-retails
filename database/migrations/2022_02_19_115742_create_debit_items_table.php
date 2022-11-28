<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebitItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debit_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('discount_type');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('base_price')->nullable();
            $table->decimal('exchange_rate', 19, 6)->nullable();
            $table->unsignedBigInteger('base_discount_val')->nullable();
            $table->unsignedBigInteger('base_tax')->nullable();
            $table->unsignedBigInteger('base_total')->nullable();
            $table->decimal('quantity', 15, 2);
            $table->string('unit_name')->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->unsignedBigInteger('discount_val');
            $table->unsignedBigInteger('tax');
            $table->unsignedBigInteger('total');
            $table->integer('debit_id')->unsigned();
            $table->foreign('debit_id')->references('id')->on('debits')->onDelete('cascade');
            $table->integer('item_id')->unsigned()->nullable();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->timestamps();
        });

        // ADD INTO TAXES
        Schema::table('taxes', function (Blueprint $table) {
            $table->unsignedInteger('debit_id')->nullable();
            $table->unsignedInteger('debit_item_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('debit_items', function (Blueprint $table) {
            $table->dropForeign(['debit_id']);
            $table->dropForeign(['item_id']);
            $table->dropForeign(['company_id']);
        });

        Schema::dropIfExists('debit_items');

        // REMOVE FROM TAXES
        Schema::table('taxes', function (Blueprint $table) {
            $table->dropColumn('debit_id');
            $table->dropColumn('debit_item_id');
        });
    }
}
