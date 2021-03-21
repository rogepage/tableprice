<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('contract_id');
            $table->unsignedInteger('member_id');
            $table->decimal('amount',8,2);
            $table->decimal('rate',8,2);
            $table->decimal('parcel_value',8,2);
            $table->integer('periods');
            $table->tinyInteger('status')->default('0');
            $table->integer('contract_ref')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('member_id')->references('member_id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}



