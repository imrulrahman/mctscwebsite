<?php

use App\Models\SiteContent;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $body = <<<HTML
<p><strong>Position:</strong> Junior Instructor (Electrical Installation & RAC Workshop)<br>
<strong>Department:</strong> Technical and Vocational Education & Training (TVET)<br>
<strong>Vacancy:</strong> 02 | <strong>Employment Status:</strong> Full Time (8:30 AM - 4:30 PM)<br>
<strong>Workplace:</strong> Sagorika Road, Pahartali, Chattogram</p>

<h4>Key Responsibilities:</h4>
<ul>
  <li>Conduct practical hands-on workshop training for trainees following BTEB and NSDA CBT&A competency standards.</li>
  <li>Prepare daily workshop lesson plans, job sheets, demonstration tasks, and instructional materials.</li>
  <li>Ensure safety standards (OHS) and proper maintenance of lab machinery, tools, and test stations.</li>
  <li>Supervise students during industrial practical assignments, project works, and workplace practice.</li>
  <li>Maintain student attendance, continuous competency assessment, and progress reports.</li>
</ul>

<h4>Educational Requirements:</h4>
<ul>
  <li>Diploma in Engineering (Electrical / Mechanical / Power) from any recognized Polytechnic Institute / BTEB.</li>
  <li>Candidates holding NSDA / BTEB CBT&A Trainer & Assessor certification will receive strong preference.</li>
</ul>

<h4>Experience Requirements:</h4>
<ul>
  <li>1 to 2 years of practical training or industrial technical experience.</li>
  <li>Fresh diploma graduates with outstanding practical skills and communication aptitude are also encouraged to apply.</li>
</ul>

<h4>Salary & Other Benefits:</h4>
<ul>
  <li>Negotiable based on qualifications, technical expertise, and experience.</li>
  <li>Festival bonuses, supportive academic work culture, and career development opportunities.</li>
</ul>

<h4>How to Apply:</h4>
<p>Click the <strong>'Apply Now'</strong> button on this page to submit your application details, or send your CV and cover note to <a href="mailto:career@mctsc.edu.bd">career@mctsc.edu.bd</a> with the subject <em>'Application for Junior Instructor'</em>.</p>
HTML;

        $existing = SiteContent::where('section', 'career')->first();
        if ($existing) {
            $existing->update(['body' => $body]);
            return;
        }

        SiteContent::create([
            'section' => 'career',
            'title' => 'Junior Instructor (Electrical Installation & RAC Workshop)',
            'slug' => 'junior-instructor-electrical-rac',
            'category' => 'Full Time · TVET Technical Training',
            'excerpt' => 'Seeking an energetic, qualified, and skilled Junior Instructor to conduct hands-on trade training, workshop sessions, and trainee competency assessment under BTEB and NSDA curriculum standards.',
            'body' => $body,
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
