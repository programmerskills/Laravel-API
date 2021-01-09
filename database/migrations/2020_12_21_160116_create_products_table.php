<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('productname')->nulllable();
            $table->double('saleprice')->nullable();
            $table->double('reg_price')->nullable();
            $table->string('pslug')->nulllable();
            $table->integer('catid')->nulllable();
            $table->integer('subcatid')->nulllable();
            $table->longText('short_desc')->nulllable();
            $table->longText('long_desc')->nulllable();
            $table->string('image1')->nulllable();
            $table->string('image2')->nulllable();
            $table->string('image3')->nulllable();
            $table->string('image4')->nulllable();

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
        Schema::dropIfExists('products');
    }
}
