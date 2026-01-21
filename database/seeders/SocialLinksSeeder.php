<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialLinks = [
            [
                'platform' => 'facebook',
                'name' => 'Facebook',
                'url' => 'https://facebook.com/iriucbc',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'platform' => 'twitter',
                'name' => 'Twitter',
                'url' => 'https://twitter.com/iriucbc',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'platform' => 'linkedin',
                'name' => 'LinkedIn',
                'url' => 'https://linkedin.com/company/iriucbc',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'platform' => 'youtube',
                'name' => 'YouTube',
                'url' => 'https://youtube.com/@iriucbc',
                'is_active' => false,
                'order' => 4,
            ],
            [
                'platform' => 'instagram',
                'name' => 'Instagram',
                'url' => 'https://instagram.com/iriucbc',
                'is_active' => false,
                'order' => 5,
            ],
        ];

        foreach ($socialLinks as $link) {
            SocialLink::updateOrCreate(
                ['platform' => $link['platform']],
                $link
            );
        }
    }
}
