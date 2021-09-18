<?php

use App\Models\Season;
use App\Models\TvShow;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create(Season::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->unique();
            $table->foreignId('tv_show_id')->constrained(TvShow::table());
            $table->integer('number')->unsigned();
            $table->string('gdrive_id')->unique();
            $table->string('name');
            $table->text('overview')->nullable();
            $table->string('poster_path')->nullable();
            $table->date('released_at')->nullable();
        });
    }
};
