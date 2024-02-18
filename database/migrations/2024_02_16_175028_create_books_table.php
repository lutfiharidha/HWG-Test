<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('title');
            $table->text('author');
            $table->string('publisher');
            $table->string('publication_date');
            $table->integer('stock');
            $table->uuid('category_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('book_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
