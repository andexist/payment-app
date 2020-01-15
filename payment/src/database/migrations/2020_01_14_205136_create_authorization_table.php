<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAuthorizationTable
 */
class CreateAuthorizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorization', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('client_id')->index()->nullable();
            $table->integer('account_id')->index()->nullable();
            $table->integer('payment_id')->index()->nullable();
            $table->string('code')->nullable();
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
        Schema::dropIfExists('authorization');
    }
}
