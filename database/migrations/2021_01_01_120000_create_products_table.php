<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->index();
            $table->string('sku')->unique(); // Internal reference name
            $table->string('title'); // Shows in book online checkout flow.
            $table->unsignedInteger('amount'); // In cents.
            $table->string('tax_code')->nullable()->default('product');
            $table->unsignedInteger('location_id')->nullable();
            $table->foreignIdFor(app('user'), 'creator_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
