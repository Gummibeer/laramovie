<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nickname');
            $table->string('name');
            $table->string('trakt_token')->nullable();
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->jsonb('watched_movie_ids')->default('[]');
            $table->index('watched_movie_ids', null, 'gin');
        });
    }
};
