<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Payment;

/**
 * Class CreatePaymentsTable
 */
class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('account_id')->index();
            $table->double('fee', 20, 2)->nullable();
            $table->double('amount', 20, 2)->nullable();
            $table->string('currency');
            $table->string('payer_account');
            $table->string('payer_name');
            $table->string('receiver_account');
            $table->string('receiver_name');
            $table->string('details');
            $table->enum('status', [
                Payment::STATUS_WAITING,
                Payment::STATUS_REJECTED,
                Payment::STATUS_APPROVED,
                Payment::STATUS_COMPLETED,
            ])->default(Payment::STATUS_WAITING);
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
        Schema::dropIfExists('payments');
    }
}
