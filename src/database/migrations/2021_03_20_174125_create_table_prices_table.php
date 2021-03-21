<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_prices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('price_id');
            $table->unsignedInteger('contract_id');
            $table->integer('quota');
            $table->decimal('balance_due',8,2);
            $table->decimal('fees',8,2);
            $table->decimal('amortization',8,2);
            $table->integer('payment_status')->default('0');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('contract_id')->references('contract_id')->on('contracts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_prices');
    }
}

