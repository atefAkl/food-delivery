<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // إنشاء تقييمات إيجابية
        Review::factory()
            ->count(10)
            ->positive()
            ->create();

        // إنشاء تقييمات سلبية
        Review::factory()
            ->count(5)
            ->negative()
            ->create();


        // إنشاء تقييمات بتقييم محدد
        Review::factory()
            ->count(3)
            ->rating(1)
            ->create();
    }
}
