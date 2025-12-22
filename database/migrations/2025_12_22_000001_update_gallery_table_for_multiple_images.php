<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gallery', function (Blueprint $table) {
            // Change image_url to json for multiple images
            $table->json('images')->nullable()->after('category_id');
            $table->string('featured_image')->nullable()->after('images');

            // Keep old column temporarily for migration
            // We'll remove it in down() if needed
        });

        // Migrate existing data
        DB::table('gallery')->get()->each(function ($gallery) {
            if ($gallery->image_url) {
                DB::table('gallery')
                    ->where('id', $gallery->id)
                    ->update([
                            'images' => json_encode([$gallery->image_url]),
                            'featured_image' => $gallery->image_url,
                        ]);
            }
        });

        // Now drop the old column
        Schema::table('gallery', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery', function (Blueprint $table) {
            $table->string('image_url')->nullable();
        });

        // Migrate data back
        DB::table('gallery')->get()->each(function ($gallery) {
            if ($gallery->featured_image) {
                DB::table('gallery')
                    ->where('id', $gallery->id)
                    ->update(['image_url' => $gallery->featured_image]);
            }
        });

        Schema::table('gallery', function (Blueprint $table) {
            $table->dropColumn(['images', 'featured_image']);
        });
    }
};
