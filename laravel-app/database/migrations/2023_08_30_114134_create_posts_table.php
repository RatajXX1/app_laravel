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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);
            $table->timestamps();
        });
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId("created_by");
            $table->string("title", 255);
            $table->text("content");
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users');
        });
        Schema::create('posts_tags', function (Blueprint $table) {
            $table->foreignId("post_id");
            $table->foreignId("tag_id");
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts_tags');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('posts');
    }
};
