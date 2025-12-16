<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            // Rename columns to match the frontend interface
            $table->renameColumn('button_text', 'primary_button_text');
            $table->renameColumn('button_link', 'primary_button_url');
            $table->renameColumn('secondary_button_link', 'secondary_button_url');

            // Add missing columns
            $table->text('description')->nullable();
            $table->string('text_color')->default('#ffffff');
            $table->string('button_style')->default('default');

            // Drop unused columns
            $table->dropColumn('overlay_color');
            $table->dropColumn('text_alignment');
            $table->dropColumn('show_particles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            // Rename columns back
            $table->renameColumn('primary_button_text', 'button_text');
            $table->renameColumn('primary_button_url', 'button_link');
            $table->renameColumn('secondary_button_url', 'secondary_button_link');

            // Add back dropped columns
            $table->string('overlay_color')->default('#000000');
            $table->json('text_alignment')->nullable();
            $table->boolean('show_particles')->default(false);

            // Drop added columns
            $table->dropColumn(['description', 'text_color', 'button_style']);
        });
    }
};
