<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDetailsTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_details_temp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('routing_number');
            $table->string('telegraphic_name',18);
            $table->string('bank_name',36);
            $table->string('state',2);
            $table->string('city',25);
            $table->string('funds_transfer_status',1);
            $table->string('funds_settlement_status',1)->nullable();
            $table->string('book_entry_securities',1);
            $table->date('revised_date')->nullable();
            $table->timestamp('created_at')->useCurrent();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
