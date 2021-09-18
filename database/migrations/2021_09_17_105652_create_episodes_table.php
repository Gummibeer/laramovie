<?php

use App\Models\Episode;
use App\Models\Season;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create(Episode::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->unique();
            $table->foreignId('season_id')->constrained(Season::table());
            $table->integer('number')->unsigned();
            $table->string('name');
            $table->text('overview')->nullable();
            $table->string('still_path')->nullable();
            $table->date('released_at')->nullable();
        });
    }
};
