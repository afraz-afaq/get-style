<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
        {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('profile_image')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->tinyInteger('first_time_login')->default(\App\Helpers\Constant::FALSE);
            $table->tinyInteger('account_type');
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
