<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Occupations table
        Schema::create('occupations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('code', 20)->index();
            $table->string('sector', 100)->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        // 2. Student Batches table
        Schema::create('student_batches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->foreignId('occupation_id')->nullable()->constrained('occupations')->nullOnDelete();
            $table->string('academic_year', 20)->nullable()->index();
            $table->string('status', 20)->default('active');
            $table->timestamps();
            $table->unique(['name', 'academic_year']);
        });

        // 3. Students table
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 50)->unique()->index();
            $table->string('student_name', 180);
            $table->string('student_name_bn', 180)->nullable();
            $table->string('gender', 20)->nullable()->index(); // Male, Female, Other
            $table->date('date_of_admission')->nullable();
            $table->string('course_type', 100)->nullable(); // Short Course, RPL, etc.
            $table->string('section', 100)->nullable()->index(); // RMG, ICT, Construction, Informal, OSH, RPL
            $table->string('occupation', 180)->index();
            $table->string('occupation_code', 20)->nullable()->index();
            $table->string('rpl_course', 180)->nullable();
            $table->string('academic_year', 20)->index(); // 2018, 2024, 2025, 2025-2026
            $table->string('session', 50)->nullable()->index(); // Session 1, Session 2, etc.
            $table->unsignedTinyInteger('session_number')->default(1);
            $table->string('batch_no', 50)->nullable()->index();
            $table->string('shift', 30)->nullable(); // Morning, Day, Evening
            $table->string('donor', 100)->nullable()->index(); // IMI, ECHO, BRAC, SOS, Self, etc.
            $table->string('phone', 50)->nullable()->index();
            $table->string('alt_phone', 50)->nullable();
            $table->string('parent_phone', 50)->nullable();
            $table->string('email', 120)->nullable();
            $table->date('dob')->nullable();
            $table->unsignedSmallInteger('age')->nullable();
            $table->string('ident_type', 30)->nullable(); // NID, Birth Certificate
            $table->string('nid_birth_cert', 60)->nullable()->index();
            $table->string('educational_qualification', 150)->nullable();
            $table->string('father_name', 150)->nullable();
            $table->string('mother_name', 150)->nullable();
            $table->string('siblings', 50)->nullable();
            $table->string('marital_status', 30)->nullable();
            $table->text('address')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('ethnic', 10)->default('No')->index(); // Yes, No
            $table->string('pwd', 10)->default('No')->index(); // Yes, No
            $table->string('photo', 255)->nullable();
            $table->string('payment_status', 50)->nullable();
            $table->string('training_status', 50)->default('Active')->index(); // Active, Completed, Running, Dropped
            
            // Assessment
            $table->decimal('viva_mark', 6, 2)->nullable();
            $table->decimal('theory_mark', 6, 2)->nullable();
            $table->decimal('practical_mark', 6, 2)->nullable();
            $table->decimal('total_marks', 6, 2)->nullable();
            $table->string('grade', 20)->nullable();
            $table->string('nsda_bteb_result', 100)->nullable(); // Competent, Not Yet Competent

            // Certificate
            $table->string('certificate_status', 50)->default('Pending')->index(); // Issued, Pending, Not Eligible
            $table->string('certificate_no', 100)->nullable();
            $table->date('certificate_issue_date')->nullable();

            // Employment
            $table->string('job_status', 50)->default('Not Yet Employed')->index(); // Employed, Self Employed, Not Yet Employed, Seeking
            $table->string('company_name', 180)->nullable();
            $table->string('company_type', 50)->nullable(); // Govt, Private, Business
            $table->string('employer_phone', 50)->nullable();
            $table->text('company_address')->nullable();
            $table->string('designation', 120)->nullable();
            $table->string('employment_type', 50)->nullable();
            $table->date('joining_date')->nullable();
            $table->decimal('salary', 12, 2)->nullable();

            $table->text('remarks')->nullable();
            $table->foreignId('admission_id')->nullable()->constrained('admission_applications')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indexes for fast searching and filtering
            $table->index(['academic_year', 'occupation']);
            $table->index(['academic_year', 'session']);
            $table->index(['batch_no', 'academic_year']);
        });

        // 4. Student Import Batches table
        Schema::create('student_import_batches', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 255);
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('successful_rows')->default(0);
            $table->unsignedInteger('duplicate_rows')->default(0);
            $table->unsignedInteger('error_rows')->default(0);
            $table->string('status', 30)->default('completed'); // processing, completed, failed
            $table->longText('error_log')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_import_batches');
        Schema::dropIfExists('students');
        Schema::dropIfExists('student_batches');
        Schema::dropIfExists('occupations');
    }
};
