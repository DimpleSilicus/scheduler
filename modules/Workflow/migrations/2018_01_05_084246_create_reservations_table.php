<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->default('pending');   //pending, confirmed, or rejected
            $table->string('respond_phone_number');
            $table->text('message');
            $table->integer('vacation_property_id')->unsigned();   //FK of vacation_properties                  
            $table->integer('user_id')->unsigned();  //FK of Users.
            
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
        Schema::dropIfExists('reservations');
    }
}
