<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoicesTable extends Migration
{
    public function up()
    {
        Schema::create('voices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('model_id'); // This is the ID from OpenAI (e.g. 'nova', 'onyx', etc.)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voices');
    }
}

