<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
        $table->string('account_name')->nullable();
        $table->string('bank_name')->nullable();
        $table->string('account_no')->nullable();
        $table->string('iban')->nullable();
        $table->string('swift_code')->nullable();
        $table->string('account_name_ar')->nullable();
        $table->string('bank_name_ar')->nullable();
        $table->string('account_no_ar')->nullable();
        $table->string('iban_ar')->nullable();
        $table->string('swift_code_ar')->nullable();
        $table->string('name_ar')->nullable();
        $table->string('city_ar')->nullable();
        $table->string('zip_ar')->nullable();
        $table->string('state_ar')->nullable();
        $table->string('phone_ar')->nullable();
        $table->string('address_street_1_ar')->nullable();
        $table->string('address_street_2_ar')->nullable();
        $table->string('cr_ar')->nullable();
        $table->string('vat_ar')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('account_name');
            $table->dropColumn('bank_name');
            $table->dropColumn('account_no');
            $table->dropColumn('iban');
            $table->dropColumn('swift_code');
            $table->dropColumn('account_name_ar');
            $table->dropColumn('bank_name_ar');
            $table->dropColumn('account_no_ar');
            $table->dropColumn('iban_ar');
            $table->dropColumn('swift_code_ar');
            $table->dropColumn('name_ar');
            $table->dropColumn('state_ar');
            $table->dropColumn('city_ar');
            $table->dropColumn('zip_ar');
            $table->dropColumn('address_street_1_ar');
            $table->dropColumn('address_street_2_ar');
            $table->dropColumn('cr_ar');
            $table->dropColumn('vat_ar');
            $table->dropColumn('phone_ar');

        });
    }
}
