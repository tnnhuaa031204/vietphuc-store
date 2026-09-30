<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class CategoryAndProductSeeder extends Seeder
{
    public function run(): void
    {
        $cat1 = Category::create([
            'name' => 'Áo Nhật Bình',
            'slug' => 'ao-nhat-binh',
            'description' => 'Trang phục cổ truyền dành cho các bậc hậu phi, công chúa.'
        ]);

        $cat2 = Category::create([
            'name' => 'Áo Ngũ Thân',
            'slug' => 'ao-ngu-than',
            'description' => 'Trang phục truyền thống lịch sự, chỉn chu.'
        ]);

        $cat3 = Category::create([
            'name' => 'Phụ Kiện Truyền Thống',
            'slug' => 'phu-kien-truyen-thong',
            'description' => 'Khăn Bandana, túi gấm, họa tiết Đông Sơn, Mây Như Ý.'
        ]);

        Product::create([
            'category_id' => $cat1->id,
            'name' => 'Áo Nhật Bình Lụa Tơ Tằm',
            'slug' => 'ao-nhat-binh-lua-to-tam',
            'description' => 'Thêu hoa văn cổ truyền sắc nét, chất liệu lụa cao cấp.',
            'price' => 3500000,
            'stock' => 10,
            'material' => 'Lụa tơ tằm',
            'image' => 'images/products/nhat-binh.png',
            'is_active' => true
        ]);

        Product::create([
            'category_id' => $cat2->id,
            'name' => 'Áo Ngũ Thân Tay Chẽn',
            'slug' => 'ao-ngu-than-tay-chen',
            'description' => 'Phom dáng chuẩn truyền thống, phù hợp sự kiện, lễ tết.',
            'price' => 1800000,
            'stock' => 15,
            'material' => 'Gấm cao cấp',
            'image' => 'images/products/ngu-than.png',
            'is_active' => true
        ]);

        Product::create([
            'category_id' => $cat3->id,
            'name' => 'Khăn Bandana Họa Tiết Trống Đồng Đông Sơn',
            'slug' => 'khan-bandana-hoa-tiet-trong-dong-dong-son',
            'description' => 'Phụ kiện thời trang kết hợp hoa văn di sản tinh tế.',
            'price' => 250000,
            'stock' => 50,
            'material' => 'Lụa Satin',
            'image' => 'images/products/bandana.png',
            'is_active' => true
        ]);
    }
}