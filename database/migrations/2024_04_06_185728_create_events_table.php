<?php

use App\Enums\EventTypeEnum;
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
        Schema::create('events', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
            $table->enum('type', EventTypeEnum::toArray())->index()->nullable();
            $table->string('from')->index()->nullable();
            $table->string('to')->index()->nullable();
            $table->timestamp('departure')->index()->nullable();
            $table->timestamp('arrival')->index()->nullable();
            $table->json('meta')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
