<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSequenceNosAndClientDetailsIntoPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // SEQUENCE NUMBERS
            $table->mediumInteger('sequence_number')->unsigned()->nullable();
            $table->mediumInteger('customer_sequence_number')->unsigned()->nullable();

            // COMPANY DETAILS
            $table->longText('company_details');

            // CLIENT DETAILS
            $table->longText('supplier_details');

            $table->string('template_name');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('sequence_number');
            $table->dropColumn('customer_sequence_number');
            $table->dropColumn('company_details');
            $table->dropColumn('supplier_details');
            $table->dropColumn('template_name');
        });
    }
}
