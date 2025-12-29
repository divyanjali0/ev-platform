<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('route_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_customer_revision_id')->constrained('itinerary_customer_revisions')->cascadeOnDelete();
            $table->json('route_days');
            $table->json('hotels')->nullable();
            $table->json('pricing')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_details');
    }
};
