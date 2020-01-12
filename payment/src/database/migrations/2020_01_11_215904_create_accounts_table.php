<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Account;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('client_id')->index();
            $table->string('account_name');
            $table->string('iban', 21)->index();
            $table->double('amount', 20, 2)->index();
            $table->string('currency')
                ->default(Account::AVAILABLE_CURRENCIES['eur']);
            $table->index(['created_at', 'updated_at']);
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
        Schema::dropIfExists('accounts');
    }
}
