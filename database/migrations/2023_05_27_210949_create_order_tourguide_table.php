<?php

use App\Models\Order;
use App\Models\Tourguide;
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
        Schema::create('order_tourguide', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Tourguide::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('order_tourguide');
    }
};
