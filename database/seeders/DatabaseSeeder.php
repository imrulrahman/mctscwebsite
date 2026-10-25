<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\SiteContent;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\PermissionManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Roles
        $rolesData = [
            [
                'name' => 'System Admin',
                'slug' => 'system-admin',
                'description' => 'Full control over system, settings, roles, users and all features.',
                'is_system' => true,
                'permissions' => PermissionManager::defaultRolePermissions('system-admin'),
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Standard administrator with full access to site content, admissions and users.',
                'is_system' => false,
                'permissions' => PermissionManager::defaultRolePermissions('admin'),
            ],
            [
                'name' => 'Admission Officer',
                'slug' => 'admission-officer',
                'description' => 'Handles trainee admissions, registrations, verification and prints.',
                'is_system' => false,
                'permissions' => PermissionManager::defaultRolePermissions('admission-officer'),
            ],
            [
                'name' => 'Content Editor',
                'slug' => 'content-editor',
                'description' => 'Manages website courses, projects, notices, events and gallery.',
                'is_system' => false,
                'permissions' => PermissionManager::defaultRolePermissions('content-editor'),
            ],
        ];

        $systemAdminRole = null;
        foreach ($rolesData as $role) {
            $created = Role::updateOrCreate(['slug' => $role['slug']], $role);
            if ($role['slug'] === 'system-admin') {
                $systemAdminRole = $created;
            }
        }

        // 2. Seed Default System Admin User
        User::updateOrCreate(['email' => env('ADMIN_EMAIL', 'admin@mctsc.edu.bd')], [
            'name' => 'MCTSC Administrator',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'ChangeMe123!')),
            'is_admin' => true,
            'role_id' => $systemAdminRole?->id,
            'status' => 'active',
            'phone' => '+8801880919275',
        ]);

        $settings = [
            'institution_name' => 'Mahmudunnabi Chowdhury Technical School and College',
            'tagline' => 'Practical Skills · Better Opportunities',
            'address' => '273/A/520, Chodu Chowdhury Road, Sagorika, Pahartali, Chattogram',
            'phone' => '+8801880919275',
            'email' => 'mnctsc@gmail.com',
            'social_facebook' => 'https://www.facebook.com/mnctsc',
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
            $fee = 0.00;
            if ($section === 'projects') {
                if (strtoupper($title) === 'ECHO') {
                    $fee = 500.00;
                }
            }

            $content = SiteContent::firstOrCreate(['section' => $section, 'title' => $title], [
                'slug' => $slug,
                'category' => $category,
                'excerpt' => $copy,
                'body' => $copy,
                'image' => $image ? 'assets/'.$image : null,
                'status' => 'published',
                'sort_order' => $index,
                'admission_fee' => $fee,
            ]);

            if ($section === 'projects' && strtoupper($title) === 'ECHO') {
                $content->update(['admission_fee' => 500.00]);
            }
        }

        // 4. Seed Development Partners
        $partners = [
            ['ASSET Project', 'DTE / World Bank', 'assets/partners/asset_project.svg', 'https://asset.gov.bd', 1],
            ['BRAC', 'POWER_kfw-BRAC', 'assets/partners/brac.png', 'https://www.brac.net', 2],
            ['UNDP', 'UPPR - UNDP', 'assets/partners/undp.png', 'https://www.undp.org/bangladesh', 3],
            ['ILO', 'International Labour Organization', 'assets/partners/ilo.svg', 'https://www.ilo.org', 4],
            ['ECHO', 'European Civil Protection & Humanitarian Aid Operations', 'assets/partners/echo.png', 'https://civil-protection-humanitarian-aid.ec.europa.eu', 5],
            ['MAA International', 'Humanitarian Organization', 'assets/partners/maa_international.png', 'https://www.maainternational.org.au', 6],
            ['SOS Children\'s Villages', 'Youth Skills Development', 'assets/partners/sos.svg', 'https://www.sos-childrensvillages.org', 7],
            ['PAA', 'Development Partner', 'assets/partners/paa.png', null, 8],
            ['KfW Development Bank', 'KfW Bankengruppe', 'assets/partners/partner_kfw.png', 'https://www.kfw.de', 9],
        ];

        foreach ($partners as [$title, $category, $image, $url, $sort]) {
            SiteContent::firstOrCreate(
                ['section' => 'partners', 'title' => $title],
                [
                    'slug' => Str::slug('partner-' . $title),
                    'category' => $category,
                    'excerpt' => $url,
                    'body' => $category,
                    'image' => $image,
                    'status' => 'published',
                    'sort_order' => $sort,
                ]
            );
        }
    }
}
