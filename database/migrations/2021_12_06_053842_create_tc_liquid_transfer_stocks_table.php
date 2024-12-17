<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTcLiquidTransferStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tc_liquid_transfer_stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tc_init_id');
            $table->bigInteger('tc_liquid_transfer_id');
            $table->bigInteger('tc_medium_stock_id');
            $table->integer('used_stock');
            $table->tinyInteger('type');
            $table->softDeletes();
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
        Schema::dropIfExists('tc_liquid_transfer_stocks');
    }
}
