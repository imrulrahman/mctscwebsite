<?php

namespace Database\Seeders;

use App\Models\SiteContent;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => env('ADMIN_EMAIL', 'admin@mctsc.edu.bd')], [
            'name' => 'MCTSC Administrator',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'ChangeMe123!')),
            'is_admin' => true,
        ]);

        $settings = [
            'institution_name' => 'Mahmudunnabi Chowdhury Technical School and College',
            'tagline' => 'Practical Skills · Better Opportunities',
            'address' => 'Chattogram, Bangladesh',
            'phone' => '+880 1XXX-XXXXXX',
            'email' => 'info@mctsc.edu.bd',
            'about' => 'Mahmudunnabi Chowdhury Technical School and College provides quality technical and vocational education to equip learners with practical skills for a brighter future. Through hands-on learning, supportive guidance and industry-relevant training, we help people build confidence, enter the workforce and create better opportunities for themselves.',
            'hero_title' => 'Practical Skills. Better Opportunities.',
            'hero_subtitle' => 'Skills-focused technical training for a confident future.',
        ];
        foreach ($settings as $key => $value) SiteSetting::firstOrCreate(['key' => $key], ['value' => $value]);

        $rows = [
            ['courses','Computer Operation','IT & Computer','Computer Operation', 'Computer Lab.jpg'],
            ['courses','Refrigeration and Air Conditioning','Mechanical','Hands-on RAC workshop training.', 'RAC Lab.jpg'],
            ['courses','Plumbing','Construction','Practical plumbing workshop skills.', 'Plumbing Class.jpg'],
            ['courses','Driving','Transport','Driving training with practical instruction.', 'Driving class with car.jpg'],
            ['courses','Sewing Machine Operation','Vocational Skills','Sewing-machine operation and practical tailoring.', 'SMO Lab.jpg'],
            ['courses','Electrical Installation','Electrical','Electrical installation and maintenance practice.', 'EIM Students.jpg'],
            ['projects','NHRDF','Training initiative','Skills development and training support initiative.', null],
            ['projects','ECHO','Training initiative','Community development through education and skills.', null],
            ['projects','ASSET','Training initiative','Advancing skills for sustainable employment and growth.', null],
            ['notices','Admission process for the next session','Admissions','Admission information will be updated by the institution.', null],
            ['notices','Practical class schedules','Academic','Practical class schedules are updated regularly.', null],
            ['notices','College notice board is now online','General','Official notices are now available on the website.', null],
            ['events','Orientation Program','Upcoming event','College Auditorium', null],
            ['events','Practical Exam','Upcoming event','Respective Departments', null],
            ['events','Cultural Program','Upcoming event','College Premises', null],
            ['gallery','Computer Operation class','Gallery','Students in the computer lab.', 'Computer Lab.jpg'],
            ['gallery','Refrigeration and AC workshop','Gallery','Practical workshop session.', 'RAC Lab.jpg'],
            ['gallery','Plumbing workshop','Gallery','Plumbing practical class.', 'Plumbing Class.jpg'],
            ['gallery','Driving training','Gallery','Practical driving lesson.', 'Driving class with car.jpg'],
            ['gallery','Sewing Machine Operation','Gallery','Sewing class.', 'SMO Lab.jpg'],
            ['gallery','College campus','Gallery','Campus building.', 'MC Building Photo.jpg'],
        ];

        foreach ($rows as $index => [$section, $title, $category, $copy, $image]) {
            $slug = Str::slug($section.'-'.$title);
            SiteContent::firstOrCreate(['section' => $section, 'title' => $title], [
                'slug' => $slug,
                'category' => $category,
                'excerpt' => $copy,
                'body' => $copy,
                'image' => $image ? 'assets/'.$image : null,
                'status' => 'published',
                'sort_order' => $index,
            ]);
        }
    }
}
