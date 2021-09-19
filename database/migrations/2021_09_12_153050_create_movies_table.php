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
            $table->string('disk');
            $table->string('directory');
            $table->string('name');
            $table->string('imdb_id')->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->string('poster_path')->nullable();
            $table->date('released_at')->nullable();
            $table->integer('runtime')->nullable();
            $table->float('vote_average')->default(0);
            $table->json('genres')->default('[]');
            $table->jsonb('cast_ids')->default('[]');
            $table->index('cast_ids', null, 'gin');
            $table->jsonb('crew_ids')->default('[]');
            $table->index('crew_ids', null, 'gin');

            $table->unique(['disk', 'directory']);
        });
    }
};
