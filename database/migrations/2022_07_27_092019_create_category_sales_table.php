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
        // ini_set('memory_limit', 4096);

        Schema::create('category_sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->uuid("proces_type")->default(0); // 0 after create 1 to first process  2 to second process 3 first And second process  هاي مال معامل مشترك وعامرية وفروسية
            $table->string('place');
            $table->string('name_customer');
            $table->string('type');
            $table->string('degree');
            $table->double('quantity');
            $table->integer('man_buliding'); // number of buliding man_buliding 
            $table->integer('workers'); // number of buliding workers 
            $table->string('bump');
            $table->string('time');
            $table->date('date');
            $table->uuid('representative_id');
            $table->string('phone_number');
            $table->double('price');
            $table->double('actual_quantity')->default(0);
            $table->text('notes')->nullable();
            $table->integer("status")->default(0); // 0 in sale category  1 in process 2 wait done  3 process done  
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