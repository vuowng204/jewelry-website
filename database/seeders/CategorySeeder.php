<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Nhẫn',
                'slug' => 'nhan',
                'description' => 'Nhẫn thời trang và trang sức cao cấp.',
                'image' => url('/images/anh_nhan.png'),
                'sort_order' => 1,
            ],
            [
                'name' => 'Bông tai',
                'slug' => 'bong-tai',
                'description' => 'Bông tai thanh lịch cho mọi dịp.',
                'image' => null,
                'sort_order' => 2,
            ],
            [
                'name' => 'Vòng cổ',
                'slug' => 'vong-co',
                'description' => 'Vòng cổ tinh tế, sang trọng.',
                'image' => null,
                'sort_order' => 3,
            ],
            [
                'name' => 'Vòng tay',
                'slug' => 'vong-tay',
                'description' => 'Vòng tay hiện đại và quý phái.',
                'image' => null,
                'sort_order' => 4,
            ],
            [
                'name' => 'Phụ kiện cưới',
                'slug' => 'phu-kien-cuoi',
                'description' => 'Trang sức và phụ kiện dành cho ngày cưới.',
                'image' => null,
                'sort_order' => 5,
            ],
            [
                'name' => 'Charm & mặt dây',
                'slug' => 'charm-mat-day',
                'description' => 'Charm cá nhân hóa và mặt dây nổi bật.',
                'image' => null,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                [
                    ...$category,
                    'parent_id' => null,
                    'is_active' => true,
                ],
            );
        }
    }
}
