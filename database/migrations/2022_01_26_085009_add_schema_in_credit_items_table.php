<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchemaInCreditItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $isSchemaAlreadyCreated = Schema::hasTable('credit_items') ? true : false;
        $schemaCreateOrAlter = $isSchemaAlreadyCreated ? 'table' : 'create';

        Schema::$schemaCreateOrAlter('credit_items', function (Blueprint $table) use ($isSchemaAlreadyCreated) {

            if($isSchemaAlreadyCreated){
                $table->increments('id')->change();
            }
            else{
                $table->increments('id');
            }

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
            $table->integer('credit_id')->unsigned();
            $table->foreign('credit_id')->references('id')->on('credits')->onDelete('cascade');
            $table->integer('item_id')->unsigned()->nullable();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_items');
    }
}
