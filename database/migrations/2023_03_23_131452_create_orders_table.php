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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->morphs('orderable');
            $table->foreignId('event_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('tourguide_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected', 'declined'])->default('pending');
            $table->enum('agent_status', ['pending', 'approved', 'rejected', 'declined'])->default('pending');
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
        Schema::dropIfExists('orders');
    }
};
