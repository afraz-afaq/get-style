<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopStylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_stylists', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('stylist_id');
            $table->integer('experience')->default(0);
            $table->integer('total_reviews')->nullable();
            $table->decimal('avg_rating', 2, 1);
            $table->tinyInteger('is_available')->default(\App\Helpers\Constant::TRUE);
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
        Schema::dropIfExists('shop_stylists');
    }
}
