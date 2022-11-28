<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('account_name')->nullable();
            $table->string('account_name_ar')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_name_ar')->nullable();
            $table->string('account_no')->nullable();
            $table->string('account_no_ar')->nullable();
            $table->string('iban')->nullable();
            $table->string('iban_ar')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('swift_code_ar')->nullable();
            $table->string('company_id')->nullable();
            $table->string('creator_id')->nullable();
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
        Schema::dropIfExists('bank_details');
    }
}
