<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingsTable extends Migration
{
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('method'); //tranferencia, tarjeta, efectivo
            $table->decimal('total', 8, 2);
            $table->timestamps();
            $table->unsignedBigInteger('id_order');

            $table->foreign('id_order')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shippings');
    }
}