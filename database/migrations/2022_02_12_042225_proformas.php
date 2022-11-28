<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Proformas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proformas', function (Blueprint $table) {
            $table->increments('id');
            $table->date('proforma_date');
            $table->date('due_date')->nullable();
            $table->string('proforma_number');
            $table->string('reference_number')->nullable();
            $table->mediumInteger('sequence_number')->unsigned()->nullable();
            $table->mediumInteger('customer_sequence_number')->unsigned()->nullable();
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
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('creator_id')->nullable();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->string('template_name')->nullable();
            $table->decimal('exchange_rate', 19, 6)->nullable();
            $table->unsignedBigInteger('base_discount_val')->nullable();
            $table->unsignedBigInteger('base_sub_total')->nullable();
            $table->unsignedBigInteger('base_total')->nullable();
            $table->unsignedBigInteger('base_tax')->nullable();
            $table->unsignedBigInteger('base_due_amount')->nullable();
            $table->date('due_date')->nullable()->change();
            $table->unsignedInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->string('prepared_by_id')->nullable();
            $table->string('deduction_per_item')->nullable();
            $table->string('comp_name')->nullable();
            $table->string('comp_name_ar')->nullable();
            $table->string('comp_cr')->nullable();
            $table->string('comp_cr_ar')->nullable();
            $table->string('comp_vat')->nullable();
            $table->string('comp_vat_ar')->nullable();
            $table->string('comp_phone_ar')->nullable();
            $table->string('comp_state_ar')->nullable();
            $table->string('comp_city_ar')->nullable();
            $table->string('comp_zip_ar')->nullable();
            $table->string('comp_address_street_1_ar')->nullable();
            $table->string('comp_address_street_2_ar')->nullable();
            $table->string('comp_phone')->nullable();
            $table->string('comp_state')->nullable();
            $table->string('comp_city')->nullable();
            $table->string('comp_zip')->nullable();
            $table->string('comp_address_street_1')->nullable();
            $table->string('comp_address_street_2')->nullable();
            $table->string('comp_account_name_ar')->nullable();
            $table->string('comp_bank_name_ar')->nullable();
            $table->string('comp_account_no_ar')->nullable();
            $table->string('comp_iban_ar')->nullable();
            $table->string('comp_swift_code_ar')->nullable();
            $table->string('comp_account_name')->nullable();
            $table->string('comp_bank_name')->nullable();
            $table->string('comp_account_no')->nullable();
            $table->string('comp_iban')->nullable();
            $table->string('comp_swift_code')->nullable();
            $table->string('cus_prefix')->nullable();
            $table->string('cus_prefix_ar')->nullable();
            $table->string('cus_website_ar')->nullable();
            $table->string('cus_website')->nullable();
            $table->string('cus_state_ar')->nullable();
            $table->string('cus_city_ar')->nullable();
            $table->string('cus_address_street_1_ar')->nullable();
            $table->string('cus_address_street_2_ar')->nullable();
            $table->string('cus_phone_ar')->nullable();
            $table->string('cus_zip_ar')->nullable();
            $table->string('cus_state')->nullable();
            $table->string('cus_city')->nullable();
            $table->string('cus_address_street_1')->nullable();
            $table->string('cus_address_street_2')->nullable();
            $table->string('cus_phone')->nullable();
            $table->string('cus_zip')->nullable();
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
        Schema::dropIfExists('proformas');
    }
}
