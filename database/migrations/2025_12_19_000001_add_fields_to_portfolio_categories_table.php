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
        // Check if columns don't exist before adding them
        Schema::table('portfolio_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('portfolio_categories', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('portfolio_categories', 'status')) {
                $table->boolean('status')->default(true)->after('description');
            }
            if (!Schema::hasColumn('portfolio_categories', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolio_categories', function (Blueprint $table) {
            if (Schema::hasColumn('portfolio_categories', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('portfolio_categories', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('portfolio_categories', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
};
