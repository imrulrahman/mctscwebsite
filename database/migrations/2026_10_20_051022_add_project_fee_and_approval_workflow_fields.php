<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Add admission_fee to site_contents for projects
        if (!Schema::hasColumn('site_contents', 'admission_fee')) {
            Schema::table('site_contents', function (Blueprint $table) {
                $table->decimal('admission_fee', 10, 2)->default(0.00)->after('category');
            });
        }

        // 2. Add project, fee, discount, and approval fields to admission_applications
        Schema::table('admission_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('admission_applications', 'project_name')) {
                $table->string('project_name', 150)->nullable()->default('Paid Batch')->after('course');
            }
            if (!Schema::hasColumn('admission_applications', 'course_fee')) {
                $table->decimal('course_fee', 10, 2)->default(6000.00)->after('project_name');
            }
            if (!Schema::hasColumn('admission_applications', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0.00)->after('course_fee');
            }
            if (!Schema::hasColumn('admission_applications', 'payable_amount')) {
                $table->decimal('payable_amount', 10, 2)->default(6000.00)->after('discount');
            }
            if (!Schema::hasColumn('admission_applications', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->default(0.00)->after('payable_amount');
            }
            if (!Schema::hasColumn('admission_applications', 'payment_status')) {
                $table->string('payment_status', 20)->default('unpaid')->after('paid_amount'); // unpaid, paid, free
            }
            if (!Schema::hasColumn('admission_applications', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('admission_applications', 'payment_trx_id')) {
                $table->string('payment_trx_id', 100)->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('admission_applications', 'voucher_no')) {
                $table->string('voucher_no', 40)->nullable()->after('payment_trx_id');
            }
            if (!Schema::hasColumn('admission_applications', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('admission_applications', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('approved_at');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('site_contents', 'admission_fee')) {
            Schema::table('site_contents', function (Blueprint $table) {
                $table->dropColumn('admission_fee');
            });
        }

        Schema::table('admission_applications', function (Blueprint $table) {
            $table->dropColumn([
                'project_name', 'course_fee', 'discount', 'payable_amount',
                'paid_amount', 'payment_status', 'payment_method', 'payment_trx_id',
                'voucher_no', 'approved_at', 'approved_by'
            ]);
        });
    }
};
