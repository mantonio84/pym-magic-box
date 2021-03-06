<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PmbCreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pmb_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();			
            $table->string("customer_id")->index();
            $table->string("order_ref")->index();
            $table->string("currency_code",3);
            $table->string("transaction_ref")->nullable();
            $table->unsignedDecimal('amount', 12, 2);
            $table->unsignedDecimal('refunded_amount', 12, 2)->default(0);
            $table->dateTime("billed_at")->nullable();
            $table->dateTime("confirmed_at")->nullable();            
            $table->unsignedBigInteger("performer_id");
            $table->unsignedInteger("alias_id")->nullable();
            $table->string("tracker")->nullable()->index();
            $table->json("other_data")->nullable();
            $table->foreign("performer_id")->references('id')->on('pmb_performers')->onDelete('cascade');
            $table->foreign("alias_id")->references('id')->on('pmb_aliases')->onDelete('set null')->onUpdate("cascade");
            
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pmb_payments');
    }
}
