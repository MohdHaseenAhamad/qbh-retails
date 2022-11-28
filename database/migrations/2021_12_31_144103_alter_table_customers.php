<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('website_ar')->nullable();
            $table->string('prefix_ar')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('state_ar')->nullable();
            $table->string('city_ar')->nullable();
            $table->string('address_street_1_ar')->nullable();
            $table->string('address_street_2_ar')->nullable();
            $table->string('phone_ar')->nullable();
            $table->string('zip_ar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('website_ar');
            $table->dropColumn('prefix_ar');
            $table->dropColumn('name_ar');
            $table->dropColumn('state_ar');
            $table->dropColumn('city_ar');
            $table->dropColumn('address_street_1_ar');
            $table->dropColumn('address_street_2_ar');
            $table->dropColumn('phone_ar');
            $table->dropColumn('zip_ar');

        });
    }
}
