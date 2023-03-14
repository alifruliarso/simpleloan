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
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('loan_id');
            $table->dateTime('due_date', $precision = 0);
            $table->decimal('due_amount', $precision = 13, $scale = 2);
            $table->decimal('paid_amount', $precision = 13, $scale = 2)->default(0);
            $table->dateTime('paid_at', $precision = 0)->nullable();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('repayments');
    }
};
