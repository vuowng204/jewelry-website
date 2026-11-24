<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'nhan' => [
                'Nhẫn Nguyệt Quang',
                'Nhẫn Hoa Hồng',
                'Nhẫn Kim Cương Aster',
                'Nhẫn Bạc Trơn Elegance',
                'Nhẫn Vàng Tình Yêu',
            ],
            'bong-tai' => [
                'Bông Tai Trái Tim',
                'Bông Tai Ngọc Trai',
                'Bông Tai Đá Sapphire',
                'Bông Tai Dây Cài',
                'Bông Tai Bạc Audrey',
            ],
            'vong-co' => [
                'Dây Chuyền Mặt Trăng',
                'Dây Chuyền Kim Cương',
                'Dây Chuyền Vàng Lily',
                'Dây Chuyền Ngọc Trai',
                'Dây Chuyền Hạt Bạc',
            ],
            'vong-tay' => [
                'Vòng Tay Charm Rose',
                'Vòng Tay Bạc Minimal',
                'Vòng Tay Vàng Classic',
                'Vòng Tay Đá Ruby',
                'Vòng Tay Cặp',
            ],
            'phu-kien-cuoi' => [
                'Nhẫn Cưới Diamond',
                'Bông Tai Cô Dâu',
                'Vương Miện Tiểu Thư',
                'Trâm Cài Tóc Pearl',
                'Lắc Tay Cô Dâu',
            ],
            'charm-mat-day' => [
                'Charm Hình Ngôi Sao',
                'Charm Hình Trái Tim',
                'Mặt Dây Chữ Số',
                'Charm Con Mèo May Mắn',
                'Mặt Dây Họa Tiết Hoa',
            ],
        ];

        $materials = [
            'Vàng 18K',
            'Bạc 925',
            'Vàng 14K',
            'Thép không gỉ',
            'Bạc mạ vàng',
        ];

        $thumbnails = [
            url('/images/anh_nhan.png'),
            url('/images/anh_nguoi_mau.png'),
            url('/images/anh_cua_hang.png'),
        ];

        $position = 0;

        foreach ($catalog as $categorySlug => $names) {
            $category = Category::query()->where('slug', $categorySlug)->firstOrFail();

            foreach ($names as $index => $name) {
                $position++;
                $price = 890000 + (($position * 370000) % 4210000);
                $salePrice = $position % 3 === 0
                    ? (int) round($price * 0.85)
                    : null;

                Product::query()->updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'category_id' => $category->id,
                        'name' => $name,
                        'description' => "{$name} được chế tác tinh xảo, phù hợp làm quà tặng hoặc sử dụng trong các dịp đặc biệt.",
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'stock' => 8 + (($position * 7) % 43),
                        'material' => $materials[($position - 1) % count($materials)],
                        'thumbnail' => $thumbnails[($position - 1) % count($thumbnails)],
                        'is_active' => true,
                        'is_featured' => $position <= 12,
                    ],
                );
            }
        }
    }
}
