<?php

use App\Models\Person;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create(Person::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->unique();
            $table->string('imdb_id')->nullable()->unique();
            $table->string('name');
            $table->text('biography')->nullable();
            $table->string('poster_path')->nullable();
        });
    }
};
