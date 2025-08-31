<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarouselSlide;

class CarouselSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Welcome to Excellence School',
                'subtitle' => 'Where Learning Meets Innovation',
                'description' => 'Discover a world of opportunities and excellence in education. Our dedicated teachers and modern facilities create the perfect environment for your child\'s success.',
                'button_text' => 'Learn More',
                'button_url' => '#about',
                'image' => null, // Will use default image
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Academic Excellence',
                'subtitle' => 'Building Tomorrow\'s Leaders',
                'description' => 'Our comprehensive curriculum focuses on critical thinking, creativity, and character development. Join us in shaping the future.',
                'button_text' => 'Our Programs',
                'button_url' => '#programs',
                'image' => null, // Will use default image
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'State-of-the-Art Facilities',
                'subtitle' => 'Modern Learning Environment',
                'description' => 'From well-equipped classrooms to advanced science labs and sports facilities, we provide everything needed for a complete educational experience.',
                'button_text' => 'Take a Tour',
                'button_url' => '#facilities',
                'image' => null, // Will use default image
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Extracurricular Activities',
                'subtitle' => 'Beyond the Classroom',
                'description' => 'Sports, arts, music, and clubs - we offer a wide range of activities to help students discover their passions and develop their talents.',
                'button_text' => 'Explore Activities',
                'button_url' => '#activities',
                'image' => null, // Will use default image
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slideData) {
            CarouselSlide::create($slideData);
        }
    }
}
