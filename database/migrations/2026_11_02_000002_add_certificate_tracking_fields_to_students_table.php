<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dateTime('certificate_printed_at')->nullable()->after('certificate_issue_date');
            $table->dateTime('certificate_distributed_at')->nullable()->after('certificate_printed_at');
            $table->dateTime('certificate_reissued_at')->nullable()->after('certificate_distributed_at');
            $table->unsignedSmallInteger('certificate_reissue_count')->default(0)->after('certificate_reissued_at');
            $table->unsignedTinyInteger('course_duration_months')->default(3)->after('course_type');
            $table->date('session_start_date')->nullable()->after('session');
            $table->date('session_end_date')->nullable()->after('session_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_printed_at',
                'certificate_distributed_at',
                'certificate_reissued_at',
                'certificate_reissue_count',
                'course_duration_months',
                'session_start_date',
                'session_end_date'
            ]);
        });
    }
};
