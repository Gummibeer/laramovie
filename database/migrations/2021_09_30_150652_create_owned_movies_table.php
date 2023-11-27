<?php

use App\Models\OwnedMovie;
use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create(OwnedMovie::table(), static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('movie_id')->constrained(Movie::table());
            $table->string('source');
            $table->string('source_id');
            $table->integer('width')->unsigned();
            $table->integer('height')->unsigned();

            $table->unique(['source', 'source_id']);
        });
    }
};
