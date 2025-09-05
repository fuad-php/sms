<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoMeta;

class SeoMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            [
                'route_name' => 'home',
                'title' => 'Welcome to ' . \App\Helpers\SettingsHelper::getSchoolName(),
                'description' => 'Nurturing minds and building futures through quality education and character development.',
                'keywords' => 'school, education, learning, students, teachers',
            ],
            [
                'route_name' => 'contact.index',
                'title' => 'Contact ' . \App\Helpers\SettingsHelper::getSchoolName(),
                'description' => 'Get in touch with us for admissions, programs, and general inquiries.',
                'keywords' => 'contact, admissions, inquiry',
            ],
        ];

        foreach (['en', 'bn'] as $locale) {
            foreach ($defaults as $item) {
                SeoMeta::updateOrCreate(
                    ['route_name' => $item['route_name'], 'locale' => $locale],
                    [
                        'title' => $item['title'],
                        'description' => $item['description'],
                        'keywords' => $item['keywords'],
                        'og_title' => $item['title'],
                        'og_description' => $item['description'],
                        'og_image' => \App\Helpers\SettingsHelper::getSchoolLogoUrl(),
                        'canonical_url' => url('/'),
                    ]
                );
            }
        }
    }
}


