<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      Schema::create('itinerary_customer_revisions', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('itinerary_customer_id');
            $table->foreign('itinerary_customer_id')->references('id')->on('itinerary_customer')->onDelete('cascade');
            $table->string('revision_no', 150);
            $table->string('reference_no', 100)->nullable();
            $table->json('theme_ids')->nullable();
            $table->json('city_ids')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('nights')->nullable();

            $table->integer('adults')->nullable();
            $table->integer('children_6_11')->nullable();
            $table->integer('children_above_11')->nullable();
            $table->integer('infants')->nullable();

            $table->tinyInteger('hotel_rating')->nullable();
            $table->string('meal_plan', 50)->nullable();

            $table->enum('allergy_issues', ['Yes', 'No'])->nullable();
            $table->text('allergy_reason')->nullable();

            $table->string('title', 20)->nullable();
            $table->string('full_name', 150)->nullable();
            $table->string('email', 150)->nullable();

            $table->string('whatsapp_code', 10)->nullable();
            $table->string('whatsapp', 20)->nullable();

            $table->string('country', 100)->nullable();
            $table->string('nationality', 100)->nullable();

            $table->string('flight_number', 50)->nullable();
            $table->text('remarks')->nullable();

            $table->string('pickup_location')->nullable();
            $table->string('dropoff_location')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itinerary_customer_revisions');
    }
};

