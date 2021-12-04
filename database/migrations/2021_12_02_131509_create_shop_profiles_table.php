<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_profiles', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->text('about')->nullable();
            $table->text('complete_address');
            $table->text('city');
            $table->text('area');
            $table->text('lat');
            $table->text('lng');
            $table->integer('total_reviews')->nullable();
            $table->decimal('avg_rating', 2, 1)->nullable();
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
        Schema::dropIfExists('shop_profiles');
    }
}
