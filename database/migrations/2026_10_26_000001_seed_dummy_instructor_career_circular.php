<?php

use App\Models\SiteContent;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (SiteContent::where('section', 'career')->exists()) {
            return;
        }

        SiteContent::create([
            'section' => 'career',
            'title' => 'Junior Instructor (Electrical Installation & RAC Workshop)',
            'slug' => 'junior-instructor-electrical-rac',
            'category' => 'Full Time · TVET Technical Training',
            'excerpt' => 'Seeking an energetic, qualified, and skilled Junior Instructor to conduct hands-on trade training, workshop sessions, and trainee competency assessment under BTEB and NSDA curriculum standards.',
            'body' => "Position: Junior Instructor (Electrical & RAC)\nDepartment: Technical and Vocational Education & Training (TVET)\nVacancy: 02\nEmployment Status: Full Time (8:30 AM - 4:30 PM)\nWorkplace: Sagorika Road, Pahartali, Chattogram\n\nKey Responsibilities:\n• Conduct practical hands-on workshop training for trainees following BTEB and NSDA CBT&A competency standards.\n• Prepare daily workshop lesson plans, job sheets, demonstration tasks, and instructional materials.\n• Ensure safety standards (OHS) and proper maintenance of lab machinery, tools, and test stations.\n• Supervise students during industrial practical assignments, project works, and workplace practice.\n• Maintain student attendance, continuous competency assessment, and progress reports.\n\nEducational Requirements:\n• Diploma in Engineering (Electrical / Mechanical / Power) from any recognized Polytechnic Institute / BTEB.\n• Candidates holding NSDA / BTEB CBT&A Trainer & Assessor certification will receive strong preference.\n\nExperience Requirements:\n• 1 to 2 years of practical training or industrial technical experience.\n• Fresh diploma graduates with outstanding practical skills and communication aptitude are also encouraged to apply.\n\nSalary & Other Benefits:\n• Negotiable based on qualifications, technical expertise, and experience.\n• Festival bonuses, supportive academic work culture, and career development opportunities.\n\nHow to Apply:\nClick the 'Apply Now' button on this page to submit your application details, or send your CV and cover note to career@mctsc.edu.bd with the subject 'Application for Junior Instructor'.",
            'status' => 'published',
            'sort_order' => 1,
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
        ]);
    }

    public function down(): void
    {
        SiteContent::where('section', 'career')->where('slug', 'junior-instructor-electrical-rac')->delete();
    }
};
