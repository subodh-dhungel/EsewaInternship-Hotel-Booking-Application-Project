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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name',150);
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('address');
            $table->string('city');
            $table->string('district');
            $table->string('country');
            $table->decimal('latitude',10,8)->nullable();
            $table->decimal('longitude',11,8)->nullable();
            $table->tinyInteger('star_rating');
            $table->string('phone',20);
            $table->string('email');
            $table->time('checkin_time');
            $table->time('check_out_time');
            $table->string('featured_image');
            $table->boolean('is_featured');
            $table->enum('status', ['pending','approved', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
