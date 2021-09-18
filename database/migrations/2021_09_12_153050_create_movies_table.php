<?php

use App\Models\Movie;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create(Movie::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->unique();
            $table->string('gdrive_id')->unique();
            $table->string('imdb_id')->nullable()->unique();
            $table->string('name');
            $table->text('overview')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->string('poster_path')->nullable();
            $table->date('released_at')->nullable();
            $table->integer('runtime')->nullable();
            $table->float('vote_average')->default(0);
            $table->json('genres');
            $table->jsonb('person_ids')->default('[]');
            $table->index('person_ids', null, 'gin');
        });
    }
};
