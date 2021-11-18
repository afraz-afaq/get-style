<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_profile', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->text('full_address');
            $table->string('country');
            $table->string('city');
            $table->string('area');
            $table->string('lat');
            $table->string('lng');
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
        Schema::dropIfExists('account_profile');
    }
}
