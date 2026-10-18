<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section', 40)->index();
            $table->string('title', 180);
            $table->string('slug')->unique();
            $table->string('category', 120)->nullable();
            $table->string('excerpt', 1000)->nullable();
            $table->longText('body')->nullable();
            $table->string('image')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['section', 'status', 'sort_order']);
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('course', 150);
            $table->string('education', 180)->nullable();
            $table->text('message')->nullable();
            $table->string('status', 20)->default('new')->index();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->string('subject', 180)->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('admission_applications');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('site_contents');
    }
};
