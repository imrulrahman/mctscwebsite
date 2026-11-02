<?php

namespace Database\Seeders;

use App\Models\Occupation;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    public function run(): void
    {
        $occupations = [
            ['name' => 'Computer Office Application', 'code' => 'C', 'sector' => 'ICT'],
            ['name' => 'Industrial Sewing Machine Operation', 'code' => 'S', 'sector' => 'RMG'],
            ['name' => 'Electrical Installation & Maintenance', 'code' => 'E', 'sector' => 'Construction'],
            ['name' => 'Tailoring & Dress Making', 'code' => 'T', 'sector' => 'Informal'],
            ['name' => 'Driving', 'code' => 'D', 'sector' => 'Informal'],
            ['name' => 'Occupational Safety and Health', 'code' => 'OSH', 'sector' => 'OSH'],
            ['name' => 'Recognition of Prior Learning', 'code' => 'RPL', 'sector' => 'RPL'],
            ['name' => 'Graphics Designing', 'code' => 'GD', 'sector' => 'ICT'],
            ['name' => 'Graphics Design for Freelancing', 'code' => 'E', 'sector' => 'ICT'],
            ['name' => 'Plumbing & Pipe Fitting', 'code' => 'P', 'sector' => 'Construction'],
            ['name' => 'Refrigeration & Air Conditioning', 'code' => 'R', 'sector' => 'Informal'],
            ['name' => 'IT Support Service', 'code' => 'S', 'sector' => 'ICT'],
        ];

        foreach ($occupations as $occ) {
            Occupation::updateOrCreate(
                ['name' => $occ['name']],
                [
                    'code' => $occ['code'],
                    'sector' => $occ['sector'],
                    'status' => 'active'
                ]
            );
        }
    }
}
