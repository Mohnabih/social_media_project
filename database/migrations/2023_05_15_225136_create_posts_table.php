<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('posts')->cascadeOnDelete();
            $table->morphs('model');
            $table->longText('content')->nullable();
            $table->integer('index')->default(1);
            $table->unsignedTinyInteger('status')->default(0); // 0=active , 1=inactive
            $table->unsignedTinyInteger('type')->default(0); // 0=normal , 1=profile picture , 2=cover photo
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
        Schema::dropIfExists('posts');
    }
}
