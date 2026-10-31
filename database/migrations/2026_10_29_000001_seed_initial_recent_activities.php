<?php

use App\Models\SiteContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        $activities = [
            [
                'title' => "Expatriates' Welfare Minister Visits Mahmudunnabi Chowdhury Technical School and College",
                'slug' => 'expatriates-welfare-minister-visits-mahmudunnabi-chowdhury-technical-school-and-college',
                'category' => 'Official Visit',
                'excerpt' => "Expatriates' Welfare and Overseas Employment Minister Ariful Haque Choudhury visited Mahmudunnabi Chowdhury Technical School and College (MCTSC) in Pahartali, Chattogram, inspecting its technical training programs and post-training job placement initiatives.",
                'body' => '<p><strong>CHATTOGRAM, Oct 3, 2026 (BSS)</strong> — Expatriates\' Welfare and Overseas Employment Minister Ariful Haque Choudhury visited Mahmudunnabi Chowdhury Technical School and College (MCTSC), managed by Impact Initiative, in Chattogram on Saturday.</p>'
                    . '<p>During the visit to the institution located in Pahartali, Chattogram, Mohammad Saif Ullah Mansur, Chief Executive Officer (CEO) of Mahmudunnabi Chowdhury Foundation, gave the Minister a tour of the institution\'s various technical and vocational skills development training facilities. He also briefed the Minister on trainees\' skill acquisition and post-training job placement initiatives.</p>'
                    . '<p>Saif Ullah Mansur stated that the institution places special emphasis on creating employment opportunities for unemployed youth (men and women) and developing skilled human resources aligned with the growing demands of domestic and international labour markets.</p>'
                    . '<p>The training programs encompass demand-driven technical courses including Computer Applications, Driving, Electrical Installation and Maintenance, Tailoring and Dress Making, Knit Sewing Machine Operation (KSMO), Refrigeration and Air Conditioning (RAC), Plumbing Occupations, and other vocational trades.</p>'
                    . '<p>Minister Ariful Haque Choudhury expressed deep satisfaction with the institution\'s training programs, achievements, and proactive initiatives taken for sustainable employment generation through technical and vocational skills development.</p>'
                    . '<p>Md. Abdur Rahman Tarafdar, Secretary of the Ministry of Labour and Employment; Amir Humayun Mahmud Chowdhury, Chairman of Mahmudunnabi Chowdhury Foundation; and Mohammad Shamsul Islam, Deputy Secretary of the Ministry of Labour and Employment, were also present during the visit, among other distinguished guests.</p>',
                'image' => 'assets/activities/minister_visit_mctsc.jpg',
                'created_at' => Carbon::parse('2026-10-29 18:00:00'),
                'sort_order' => 1,
            ],
            [
                'title' => 'Orientation & Class Inauguration of NHRDF Free Technical Training Courses (10 Oct 2026)',
                'slug' => 'nhrdf-free-technical-training-class-inauguration-orientation',
                'category' => 'NHRDF / Class Inauguration',
                'excerpt' => 'The class inauguration and orientation ceremony for NHRDF-funded free technical training courses was successfully held on 10 October 2026 at MCTSC.',
                'body' => '<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;margin-bottom:20px;box-shadow:0 4px 18px rgba(0,0,0,0.12);">'
                    . '<iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" src="https://www.youtube.com/embed/Xhzil1T8-RI" title="NHRDF-এর অর্থায়নে বিনামূল্যে কারিগরি প্রশিক্ষণ কোর্স এর অরিয়েন্টেশন ক্লাস" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                    . '</div>'
                    . '<p><strong>CHATTOGRAM, Oct 10, 2026</strong> — The class inauguration and orientation ceremony for the new batch of free technical and vocational training courses funded by the <strong>National Human Resource Development Fund (NHRDF)</strong> was successfully held at <strong>Mahmudunnabi Chowdhury Technical School and College (MCTSC)</strong> campus in Pahartali, Chattogram.</p>'
                    . '<p>মাননীয় প্রধানমন্ত্রী কার্যালয়াধীন ন্যাশনাল হিউম্যান রিসোর্স ডেভেলপমেন্ট ফান্ড (NHRDF)-এর অর্থায়নে ও ইমপ্যাক্ট ইনিশিয়েটিভ পরিচালিত মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজে সুবিধাবঞ্চিত ও বেকার যুবক-যুবতীদের জন্য সম্পূর্ণ বিনামূল্যে কারিগরি প্রশিক্ষণ কোর্সের অরিয়েন্টেশন ও ক্লাস উদ্বোধন অনুষ্ঠিত হয়।</p>'
                    . '<p>The orientation session welcomed the newly enrolled trainees, familiarized them with the modern workshop laboratories, machinery, and training curriculum, and introduced them to expert instructors and industry mentors. Special emphasis was placed on hands-on practical competencies, workplace safety, and post-training job placement facilitation.</p>',
                'image' => 'assets/activities/nhrdf_class_orientation.jpg',
                'created_at' => Carbon::parse('2026-10-28 15:00:00'),
                'sort_order' => 2,
            ],
            [
                'title' => 'Admission Test for NHRDF Subsidized Technical Training Batch Successfully Completed (08 Oct 2026)',
                'slug' => 'admission-test-for-nhrdf-subsidized-technical-training-batch',
                'category' => 'NHRDF / Admission Test',
                'excerpt' => 'The formal admission examination and screening test for the NHRDF subsidized technical training courses was successfully completed on 08 October 2026 at MCTSC.',
                'body' => '<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;margin-bottom:20px;box-shadow:0 4px 18px rgba(0,0,0,0.12);">'
                    . '<iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" src="https://www.youtube.com/embed/GLxni5-GswQ" title="Admission Test batch of NHRDF" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                    . '</div>'
                    . '<p><strong>CHATTOGRAM, Oct 8, 2026</strong> — The admission test and candidate evaluation for the technical training batches under the <strong>National Human Resource Development Fund (NHRDF)</strong> program was successfully conducted on <strong>08 October 2026</strong> at Mahmudunnabi Chowdhury Technical School and College (MCTSC).</p>'
                    . '<p>ন্যাশনাল হিউম্যান রিসোর্স ডেভেলপমেন্ট ফান্ড (NHRDF)-এর আওতায় বিনামূল্যে পরিচালিত বিভিন্ন ট্রেডের প্রশিক্ষণ কোর্সে ভর্তির নিমিত্তে লিখিত ও ব্যবহারিক ভর্তি পরীক্ষা সুষ্ঠুভাবে সম্পন্ন হয়। চট্টগ্রামের বিভিন্ন প্রান্ত থেকে আগত বিপুল সংখ্যক তরুণ-তরুণী এই ভর্তি পরীক্ষায় অংশগ্রহণ করেন।</p>'
                    . '<p>Selected candidates are provided with full tuition waivers, practical workshops, quality learning materials, and employment linkage support upon successful completion of the training.</p>',
                'image' => 'assets/activities/nhrdf_admission_test.jpg',
                'created_at' => Carbon::parse('2026-10-27 15:00:00'),
                'sort_order' => 3,
            ],
            [
                'title' => 'Admissions Open: NHRDF-Funded Free Technical and Vocational Training Courses',
                'slug' => 'admissions-open-nhrdf-funded-free-technical-training-courses',
                'category' => 'NHRDF / Admission',
                'excerpt' => 'Admissions are currently underway for NHRDF-funded free technical and vocational training courses at Mahmudunnabi Chowdhury Technical School and College (MCTSC) in Pahartali, Chattogram.',
                'body' => '<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;margin-bottom:20px;box-shadow:0 4px 18px rgba(0,0,0,0.12);">'
                    . '<iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" src="https://www.youtube.com/embed/xZ6aU40tGt4" title="Admission going on for Technical Training of MCTSC" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                    . '</div>'
                    . '<p><strong>CHATTOGRAM</strong> — Admissions are currently ongoing for the new batch of free technical and vocational training courses funded by the <strong>National Human Resource Development Fund (NHRDF)</strong> under the Prime Minister\'s Office, conducted at <strong>Mahmudunnabi Chowdhury Technical School and College (MCTSC)</strong> campus in Pahartali, Chattogram.</p>'
                    . '<p>মাননীয় প্রধানমন্ত্রী কার্যালয়াধীন ন্যাশনাল হিউম্যান রিসোর্স ডেভেলপমেন্ট ফান্ড (NHRDF)-এর অর্থায়নে ও ইমপ্যাক্ট ইনিশিয়েটিভ পরিচালিত মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজে সুবিধাবঞ্চিত ও বেকার যুবক-যুবতীদের জন্য সম্পূর্ণ বিনামূল্যে কর্মসংস্থানমুখী বিভিন্ন কারিগরি প্রশিক্ষণ কোর্সে ভর্তি চলছে।</p>'
                    . '<p>The training courses offer comprehensive theoretical knowledge and hands-on practical workshop training designed to develop high-demand market-oriented skills. Eligible youth (men and women) receive full tuition waivers, state-of-the-art laboratory access, practical consumables, and dedicated job placement assistance upon course completion.</p>'
                    . '<p><strong>Available Trades Include:</strong></p>'
                    . '<ul>'
                    . '<li>Computer Applications & Office Management</li>'
                    . '<li>Electrical Installation and Maintenance</li>'
                    . '<li>Refrigeration and Air Conditioning (RAC)</li>'
                    . '<li>Knit Sewing Machine Operation (KSMO) & Tailoring</li>'
                    . '<li>Professional Driving & Auto-mechanics</li>'
                    . '<li>Plumbing and Sanitary Occupations</li>'
                    . '</ul>'
                    . '<p>Interested applicants are encouraged to visit the MCTSC campus or submit their admission application online through our official website portal.</p>',
                'image' => 'assets/activities/nhrdf_admission_going_on.jpg',
                'created_at' => Carbon::parse('2026-10-26 12:00:00'),
                'sort_order' => 4,
            ],
        ];

        foreach ($activities as $act) {
            SiteContent::updateOrCreate(
                ['section' => 'activities', 'slug' => $act['slug']],
                [
                    'title' => $act['title'],
                    'category' => $act['category'],
                    'excerpt' => $act['excerpt'],
                    'body' => $act['body'],
                    'image' => $act['image'],
                    'status' => 'published',
                    'sort_order' => $act['sort_order'],
                    'created_at' => $act['created_at'],
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        SiteContent::where('section', 'activities')->delete();
    }
};
