<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Publisher;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publishers', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        // Back-fill slugs for existing publishers
        Publisher::whereNull('slug')->orderBy('id')->each(function ($publisher) {
            $base = Str::slug($publisher->name);
            $slug = $base;
            $i = 1;
            while (Publisher::where('slug', $slug)->where('id', '!=', $publisher->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $publisher->slug = $slug;
            $publisher->saveQuietly();
        });
    }

    public function down(): void
    {
        Schema::table('publishers', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
