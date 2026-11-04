<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Activity Logs Table
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('user_name')->nullable();
                $table->string('user_email')->nullable();
                $table->string('user_role')->nullable();
                $table->string('action'); // e.g., login, failed_login, logout, update_banner, update_content, etc.
                $table->string('module'); // e.g., Security & Auth, Banners, Settings, Users, Courses, etc.
                $table->text('description'); // e.g., "রাকিব হাসান ব্যানার ইমেজ চেঞ্জ করেছেন"
                $table->nullableMorphs('subject'); // subject_type & subject_id
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->json('properties')->nullable();
                $table->timestamps();

                $table->index(['action', 'created_at']);
                $table->index(['module', 'created_at']);
                $table->index(['user_id', 'created_at']);
            });
        }

        // 2. Visitor Logs Table
        if (!Schema::hasTable('visitor_logs')) {
            Schema::create('visitor_logs', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address', 45);
                $table->string('country_code', 10)->nullable()->default('BD');
                $table->string('country_name', 100)->nullable()->default('Bangladesh');
                $table->string('city', 100)->nullable();
                $table->string('device_type', 30)->nullable()->default('Desktop');
                $table->string('browser', 50)->nullable();
                $table->string('platform', 50)->nullable();
                $table->text('page_url')->nullable();
                $table->text('referer')->nullable();
                $table->text('user_agent')->nullable();
                $table->boolean('is_unique_today')->default(true);
                $table->date('visit_date');
                $table->timestamps();

                $table->index(['visit_date', 'country_code']);
                $table->index(['ip_address', 'visit_date']);
                $table->index('country_name');
            });
        }

        // 3. Add last edit tracking fields to site_contents table
        if (Schema::hasTable('site_contents')) {
            Schema::table('site_contents', function (Blueprint $table) {
                if (!Schema::hasColumn('site_contents', 'last_edited_by')) {
                    $table->foreignId('last_edited_by')->nullable()->after('metadata')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('site_contents', 'last_editor_name')) {
                    $table->string('last_editor_name')->nullable()->after('last_edited_by');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('site_contents')) {
            Schema::table('site_contents', function (Blueprint $table) {
                if (Schema::hasColumn('site_contents', 'last_edited_by')) {
                    $table->dropForeign(['last_edited_by']);
                    $table->dropColumn('last_edited_by');
                }
                if (Schema::hasColumn('site_contents', 'last_editor_name')) {
                    $table->dropColumn('last_editor_name');
                }
            });
        }

        Schema::dropIfExists('visitor_logs');
        Schema::dropIfExists('activity_logs');
    }
};
