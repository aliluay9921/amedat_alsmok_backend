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
        Schema::create('category_sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->string('place');
            $table->string('name_customer');
            $table->string('type');
            $table->double('quantity');
            $table->integer('man_buliding'); // number of buliding man_buliding 
            $table->integer('workers'); // number of buliding workers 
            $table->string('bump');
            $table->string('time');
            $table->date('date');
            $table->string('name_representative');
            $table->string('phone_number');
            $table->double('price');
            $table->double('actual_quantity')->default(0);
            $table->text('notes')->nullable();
            $table->integer("status")->default(0);
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
        Schema::dropIfExists('category_sales');
    }
};