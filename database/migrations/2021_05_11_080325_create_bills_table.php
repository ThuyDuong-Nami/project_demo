<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\BillStatus;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_code', 10);
            $table->foreignId('user_id')
                    ->constrained('users')
                    ->onDelete('cascade');
            $table->foreignId('product_id')
                    ->constrained('products')
                    ->onDelete('cascade');
            $table->float('total');
            $table->tinyInteger('status')->unsigned()->default(BillStatus::confirm);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
