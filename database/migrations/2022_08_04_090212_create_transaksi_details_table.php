<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id('id_transaksi_detail');
            $table->integer('id_item');
            $table->integer('id_transaksi');
            $table->integer('qty');
            $table->float('subtotal');
            $table->timestamps();
            $table->softDeletes();

            $table->index('id_item');
            $table->index('id_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_details');
    }
};
