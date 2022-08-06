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
        Schema::create('invoicemnts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid("employee_id");
            $table->uuid("sale_category_id");
            $table->string("driver_name");
            $table->string("car_number");
            $table->double("quantity_car");
            $table->integer("invoice_no")->default(1);
            $table->string('sequence')->default(1);
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
        Schema::dropIfExists('invoicemnts');
    }
};