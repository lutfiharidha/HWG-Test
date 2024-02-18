<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('book_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('category_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_categories');
    }
}
