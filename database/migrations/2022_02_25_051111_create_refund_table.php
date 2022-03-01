<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unique();
            $table->unsignedBigInteger('user_id');
            $table->enum('delivery_status',['delivered','partial','cancel','processing']);
            $table->enum('type',['return','refund']);
            $table->enum('payment_method',['bank','bkash','nagad']);
            $table->float('order_amount')->nullable();
            $table->float('refund_amount');
            $table->text('reason');
            $table->longText('description');
            $table->enum('admin_status',['approved','rejected','processing'])->default('processing');
            $table->enum('user_action',['true','false'])->default('false');
            $table->text('trans_dtls')->nullable();
            $table->dateTime('user_action_time')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('refund');
    }
}
