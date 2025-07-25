<?php

use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\MovieGenre;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection(Movie::connection())->create('movie_movie_genre', static function (Blueprint $table): void {
            $table->foreignId('movie_id')->constrained(Movie::table());
            $table->foreignId('movie_genre_id')->constrained(MovieGenre::table());

            $table->unique(['movie_id', 'movie_genre_id']);
        });
    }

    public function down(): void
    {
        Schema::connection(Movie::connection())->dropIfExists('movie_movie_genre');
    }
};
