<?php

use App\Models\SiteContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
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

    public function down(): void
    {
        SiteContent::where('section', 'partners')->delete();
    }
};
