<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landing_page_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_key_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->json('name')->nullable();
            $table->json('title')->nullable();
            $table->json('content')->nullable();
            $table->string('url')->nullable();
            $table->json('button_text')->nullable();
            $table->string('button_url')->nullable();
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
        Schema::dropIfExists('landing_page_contents');
    }
};
