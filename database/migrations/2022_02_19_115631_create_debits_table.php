<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debits', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumInteger('sequence_number')->unsigned()->nullable();
            $table->mediumInteger('customer_sequence_number')->unsigned()->nullable();
            $table->string('debit_number');
            $table->date('debit_date');
            $table->string('template_name')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('status');
            $table->string('tax_per_item');
            $table->string('discount_per_item');
            $table->text('notes')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->unsignedBigInteger('discount_val')->nullable();
            $table->unsignedBigInteger('sub_total');
            $table->unsignedBigInteger('tax');
            $table->unsignedBigInteger('total');
            $table->unsignedBigInteger('base_discount_val')->nullable();
            $table->unsignedBigInteger('base_sub_total')->nullable();
            $table->unsignedBigInteger('base_total')->nullable();
            $table->unsignedBigInteger('base_tax')->nullable();    
            $table->string('deduction_per_item')->nullable();
            $table->decimal('exchange_rate', 19, 6)->nullable();
            $table->boolean('sent')->default(false);
            $table->boolean('viewed')->default(false);
            $table->string('unique_hash')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->integer('purchase_id')->unsigned();
            $table->unsignedInteger('creator_id')->nullable();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('prepared_by_id')->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('suppliers');
            $table->longText('company_details');
            $table->longText('supplier_details');
            $table->string('printed_by')->nullable();
            $table->string('upper_margin')->nullable()->default('3.5');
            $table->string('lower_margin')->nullable()->default('1.5');
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
        Schema::table('debits', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropForeign(['creator_id']);
            $table->dropForeign(['company_id']);
            $table->dropForeign(['customer_id']);
        });
        Schema::dropIfExists('debits');
    }
}
