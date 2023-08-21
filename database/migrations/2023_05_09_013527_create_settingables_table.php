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
        Schema::create('settingables', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Setting::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->morphs('settingable');
            $table->string('value')->nullable();
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
        Schema::dropIfExists('settingables');
    }
};
