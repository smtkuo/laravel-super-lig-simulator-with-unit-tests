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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_home_id')->constrained('teams');
            $table->foreignId('team_away_id')->constrained('teams');
            $table->dateTime('match_date');
            $table->string('location');
            $table->integer('home_team_goals')->nullable();
            $table->integer('away_team_goals')->nullable();
            $table->foreignId('winning_team_id')->nullable()->constrained('teams');
            $table->foreignId('losing_team_id')->nullable()->constrained('teams');
            $table->boolean('draw')->default(false);
            $table->boolean('played')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
