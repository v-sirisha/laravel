<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_rate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('parent_placement_name');
            $table->string('device_group');
            $table->string('deal_country_group');
            $table->string('deal_rate');
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
        Schema::drop('deal_rate');
        Schema::table('deal_rate', function (Blueprint $table) {
            //
        });
    }
}
