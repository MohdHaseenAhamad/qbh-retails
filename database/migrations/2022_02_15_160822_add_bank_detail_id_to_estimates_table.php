<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankDetailIdToEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
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
            $table->string('bank_detail_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn('comp_name');
            $table->dropColumn('comp_name_ar');
            $table->dropColumn('comp_cr');
            $table->dropColumn('comp_cr_ar');
            $table->dropColumn('comp_vat');
            $table->dropColumn('comp_vat_ar');
            $table->dropColumn('comp_phone_ar');
            $table->dropColumn('comp_state_ar');
            $table->dropColumn('comp_city_ar');
            $table->dropColumn('comp_zip_ar');
            $table->dropColumn('comp_address_street_1_ar');
            $table->dropColumn('comp_address_street_2_ar');
            $table->dropColumn('comp_phone');
            $table->dropColumn('comp_state');
            $table->dropColumn('comp_city');
            $table->dropColumn('comp_zip');
            $table->dropColumn('comp_address_street_1');
            $table->dropColumn('comp_address_street_2');
            $table->dropColumn('comp_account_name_ar');
            $table->dropColumn('comp_bank_name_ar');
            $table->dropColumn('comp_account_no_ar');
            $table->dropColumn('comp_iban_ar');
            $table->dropColumn('comp_swift_code_ar');
            $table->dropColumn('comp_account_name');
            $table->dropColumn('comp_bank_name');
            $table->dropColumn('comp_account_no');
            $table->dropColumn('comp_iban');
            $table->dropColumn('comp_swift_code');
            $table->dropColumn('cus_prefix');
            $table->dropColumn('cus_prefix_ar');
            $table->dropColumn('cus_website_ar');
            $table->dropColumn('cus_website');
            $table->dropColumn('cus_state_ar');
            $table->dropColumn('cus_city_ar');
            $table->dropColumn('cus_address_street_1_ar');
            $table->dropColumn('cus_address_street_2_ar');
            $table->dropColumn('cus_phone_ar');
            $table->dropColumn('cus_zip_ar');
            $table->dropColumn('cus_state');
            $table->dropColumn('cus_city');
            $table->dropColumn('cus_address_street_1');
            $table->dropColumn('cus_address_street_2');
            $table->dropColumn('cus_phone');
            $table->dropColumn('cus_zip');
            $table->dropColumn('bank_detail_id');
        });
    }
}
