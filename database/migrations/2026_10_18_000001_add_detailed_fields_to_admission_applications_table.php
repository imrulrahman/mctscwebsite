<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('admission_applications', function (Blueprint $table) {
            $table->string('tracking_no', 30)->nullable()->unique()->after('id');
            $table->string('trainee_name_en', 150)->nullable()->after('name');
            $table->string('trainee_name_bn', 150)->nullable()->after('trainee_name_en');
            $table->date('dob')->nullable()->after('trainee_name_bn');
            $table->string('ident_type', 30)->nullable()->after('dob'); // National ID, Birth Certificate
            $table->string('ident_no', 60)->nullable()->after('ident_type');
            $table->string('sex', 20)->nullable()->after('ident_no'); // Male, Female, Other
            $table->string('pwd', 10)->default('No')->after('sex'); // Person With Disability: Yes, No
            $table->string('contact_no', 30)->nullable()->after('pwd');
            $table->string('photo', 255)->nullable()->after('contact_no');
            $table->string('ident_doc', 255)->nullable()->after('photo');
            $table->string('signature_photo', 255)->nullable()->after('ident_doc');

            // Permanent Address
            $table->string('perm_division', 60)->nullable();
            $table->string('perm_district', 60)->nullable();
            $table->string('perm_upazilla', 80)->nullable();
            $table->text('perm_address')->nullable();
            $table->string('perm_area_type', 20)->nullable(); // Rural, Urban

            // Present Address
            $table->string('pres_division', 60)->nullable();
            $table->string('pres_district', 60)->nullable();
            $table->string('pres_upazilla', 80)->nullable();
            $table->text('pres_address')->nullable();
            $table->string('religion', 40)->nullable();
            $table->string('ethnicity', 40)->nullable();
            $table->string('education_level', 100)->nullable();
            $table->string('marital_status', 30)->nullable();

            // Household Profile
            $table->string('father_name_en', 150)->nullable();
            $table->string('father_name_bn', 150)->nullable();
            $table->string('mother_name_en', 150)->nullable();
            $table->string('mother_name_bn', 150)->nullable();
            $table->string('emergency_contact', 30)->nullable();
            $table->unsignedSmallInteger('household_members')->nullable();
            $table->string('income_source', 150)->nullable();
            $table->decimal('monthly_income', 12, 2)->nullable();
            $table->decimal('daily_income_per_person', 10, 2)->nullable();
            $table->string('has_bank_account', 10)->nullable(); // Yes, No

            // Skills & Past Employment
            $table->string('past_training', 10)->nullable(); // Yes, No
            $table->string('employment_status', 60)->nullable();
            $table->decimal('monthly_cash_income', 12, 2)->nullable();
            $table->string('training_media_source', 100)->nullable();

            // Course selection & Shift
            $table->string('preferred_shift', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('admission_applications', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_no', 'trainee_name_en', 'trainee_name_bn', 'dob',
                'ident_type', 'ident_no', 'sex', 'pwd', 'contact_no',
                'photo', 'ident_doc', 'signature_photo',
                'perm_division', 'perm_district', 'perm_upazilla', 'perm_address', 'perm_area_type',
                'pres_division', 'pres_district', 'pres_upazilla', 'pres_address',
                'religion', 'ethnicity', 'education_level', 'marital_status',
                'father_name_en', 'father_name_bn', 'mother_name_en', 'mother_name_bn',
                'emergency_contact', 'household_members', 'income_source',
                'monthly_income', 'daily_income_per_person', 'has_bank_account',
                'past_training', 'employment_status', 'monthly_cash_income',
                'training_media_source', 'preferred_shift'
            ]);
        });
    }
};
