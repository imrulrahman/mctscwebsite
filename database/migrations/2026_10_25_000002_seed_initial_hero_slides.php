<?php

use App\Models\SiteContent;
use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Initial 3 Hero Banner Slides
        $slides = [
            ['Robotics & Automation Lab', 'Campus Lab', 'assets/banners/banner_robotics_lab.jpg', 1],
            ['Modern Innovation Hub', 'Innovation', 'assets/banners/banner_innovation_hub.jpg', 2],
            ['Electrical & Advanced Workshop', 'Workshop', 'assets/banners/banner_electrical_lab.jpg', 3],
        ];

        foreach ($slides as [$title, $category, $image, $sort]) {
            SiteContent::firstOrCreate(
                ['section' => 'hero_slides', 'title' => $title],
                [
                    'slug' => Str::slug('hero-slide-' . $title),
                    'category' => $category,
                    'image' => $image,
                    'status' => 'published',
                    'sort_order' => $sort,
                ]
            );
        }

        // 2. Default Hero Slider Setting
        SiteSetting::firstOrCreate(['key' => 'hero_slider_mode'], ['value' => 'slide']);
        SiteSetting::firstOrCreate(['key' => 'hero_slider_interval'], ['value' => '4500']);
    }

    public function down(): void
    {
        SiteContent::where('section', 'hero_slides')->delete();
        SiteSetting::whereIn('key', ['hero_slider_mode', 'hero_slider_interval', 'hero_slider_fixed_id'])->delete();
    }
};
