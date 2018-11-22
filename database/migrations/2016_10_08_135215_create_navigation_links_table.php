<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavigationLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent')->default(0);
            $table->string('title', 64);
            $table->string('slug')->unique();
            $table->boolean('internal')->default(true);
            $table->string('value');
            $table->boolean('is_active')->default(false);
            $table->integer('sort')->default(0);
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
        Schema::dropIfExists('navigation_links');
    }
}
