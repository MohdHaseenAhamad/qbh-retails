<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('contact_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('description')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address_street_1')->nullable();
            $table->string('address_street_2')->nullable();
            $table->string('zip')->nullable();
            $table->string('state_ar')->nullable();
            $table->string('city_ar')->nullable();
            $table->string('address_street_1_ar')->nullable();
            $table->string('address_street_2_ar')->nullable();
            $table->string('zip_ar')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->integer('creator_id')->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
        // Schema::table('stores', function (Blueprint $table) {
        //     $table->dropForeign(['creator_id']);
        //     $table->dropForeign(['country_id']);
        // });

        Schema::dropIfExists('stores');
    }
}
