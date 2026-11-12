<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('job_applications')) {
            Schema::create('job_applications', function (Blueprint $table) {
                $table->id();
                $table->string('tracking_no', 40)->unique();
                $table->foreignId('site_content_id')->nullable()->constrained('site_contents')->nullOnDelete();
                $table->string('post_name');

                // 1. Personal Information
                $table->string('applicant_name');
                $table->string('father_name');
                $table->string('mother_name');
                $table->date('dob');
                $table->string('gender', 30);
                $table->string('nationality', 60)->default('Bangladeshi');
                $table->string('religion', 40);
                $table->string('nid_no', 60)->nullable();
                $table->string('birth_reg_no', 60)->nullable();
                $table->string('passport_no', 60)->nullable();
                $table->string('marital_status', 30)->default('Single');
                $table->string('spouse_name')->nullable();
                $table->string('quota', 80)->default('Non-Quota');
                $table->string('departmental_candidate', 20)->default('No');

                // 2. Addresses (JSON)
                $table->json('present_address')->nullable();
                $table->json('permanent_address')->nullable();

                // 3. Contact Information
                $table->string('mobile_no', 30);
                $table->string('email');

                // 4. Educational Qualifications & Experience (JSON)
                $table->json('education')->nullable();
                $table->json('experiences')->nullable();
                $table->text('other_qualifications')->nullable();

                // 5. Attachments
                $table->string('photo_path')->nullable();
                $table->string('signature_path')->nullable();
                $table->string('cv_path')->nullable();

                // 6. Status & Admin Notes
                $table->string('status', 30)->default('new');
                $table->text('admin_notes')->nullable();
                $table->timestamps();

                $table->index(['post_name', 'status']);
                $table->index('mobile_no');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
