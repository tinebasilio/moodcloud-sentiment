<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentimentsTable extends Migration
{
    public function up()
    {
        Schema::create('sentiments', function (Blueprint $table) {
            $table->id();
            $table->text('sentiment_input');
            $table->string('sentiment_result');
            $table->string('sentiment_emotion');
            $table->text('text_features')->nullable();
            $table->timestamp('sentiment_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sentiments');
    }
}
