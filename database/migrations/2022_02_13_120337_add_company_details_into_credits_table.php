<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyDetailsIntoCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits', function (Blueprint $table) {
            // REASON OF CREDIT NOTE CREATION
            $table->string('reason')->default('Amendment');

            // COMPANY DETAILS
            $table->longText('company_details');

            // CLIENT DETAILS
            $table->longText('client_details');

            // $table->string('comp_name');
            // $table->string('comp_name_ar');
            // $table->string('comp_city');
            // $table->string('comp_city_ar');
            // $table->string('comp_state');
            // $table->string('comp_state_ar');
            // $table->string('comp_steet1');
            // $table->string('comp_street1_ar');
            // $table->string('comp_zip');
            // $table->string('comp_zip_ar');
            // $table->string('comp_phone');
            // $table->string('comp_phone_ar');
            // $table->string('comp_fax');
            // $table->string('comp_fax_ar');
            // $table->string('comp_cr');
            // $table->string('comp_cr_ar');
            // $table->string('comp_vat');
            // $table->string('comp_vat_ar');
            // BANK DETAILS
            // $table->string('comp_bank_name');
            // $table->string('comp_bank_name_ar');
            // $table->string('comp_account_name');
            // $table->string('comp_account_name_ar');
            // $table->string('comp_account_no');
            // $table->string('comp_account_no_ar');
            // $table->string('comp_iban');
            // $table->string('comp_iban_ar');
            // $table->string('comp_swift');
            // $table->string('comp_swift_ar');
            // $table->string('comp_swift_ar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn('reason');
            $table->dropColumn('company_details');
            $table->dropColumn('client_details');

            // $table->dropColumn('comp_name');
            // $table->dropColumn('comp_name_ar');
            // $table->dropColumn('comp_city');
            // $table->dropColumn('comp_city_ar');
            // $table->dropColumn('comp_state');
            // $table->dropColumn('comp_state_ar');
            // $table->dropColumn('comp_steet1');
            // $table->dropColumn('comp_street1_ar');
            // $table->dropColumn('comp_zip');
            // $table->dropColumn('comp_zip_ar');
            // $table->dropColumn('comp_phone');
            // $table->dropColumn('comp_phone_ar');
            // $table->dropColumn('comp_fax');
            // $table->dropColumn('comp_fax_ar');
            // $table->dropColumn('comp_cr');
            // $table->dropColumn('comp_cr_ar');
            // $table->dropColumn('comp_vat');
            // $table->dropColumn('comp_vat_ar');

            // $table->dropColumn('comp_bank_name');
            // $table->dropColumn('comp_bank_name_ar');
            // $table->dropColumn('comp_account_name');
            // $table->dropColumn('comp_account_name_ar');
            // $table->dropColumn('comp_account_no');
            // $table->dropColumn('comp_account_no_ar');
            // $table->dropColumn('comp_iban');
            // $table->dropColumn('comp_iban_ar');
            // $table->dropColumn('comp_swift');
            // $table->dropColumn('comp_swift_ar');
            // $table->dropColumn('comp_swift_ar');
        });
    }
}
