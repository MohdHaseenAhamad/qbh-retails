<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("purchase_no");
            $table->string("purchase_date");
            $table->string("invoice_no")->comment('Supplier Invoice No.');
            $table->string("invoice_date")->comment('Supplier Invoice Date.');
            $table->string("order_no")->nullable();
            $table->string("order_date")->nullable();
            $table->string("material_receipt")->nullable();
            $table->string("supply_date")->nullable();

            $table->string('reference_number')->nullable();
            $table->string('status');
            $table->string('paid_status');
            $table->string('tax_per_item');
            $table->string('discount_per_item');
            $table->text('notes')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->unsignedBigInteger('discount_val')->nullable();
            $table->unsignedBigInteger('sub_total');
            $table->unsignedBigInteger('total');
            $table->unsignedBigInteger('tax');
            $table->unsignedBigInteger('due_amount');
            $table->string('upper_margin')->nullable()->default('3.5');
            $table->string('lower_margin')->nullable()->default('1.5');

            $table->boolean('sent')->default(false);
            $table->boolean('viewed')->default(false);
            $table->string('unique_hash')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');

            $table->decimal('exchange_rate', 19, 6)->nullable();
            $table->unsignedBigInteger('base_discount_val')->nullable();
            $table->unsignedBigInteger('base_sub_total')->nullable();
            $table->unsignedBigInteger('base_total')->nullable();
            $table->unsignedBigInteger('base_tax')->nullable();
            $table->unsignedBigInteger('base_due_amount')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
