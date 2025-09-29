<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $iphones = Category::where('name', 'iPhones')->first();
        $macbooks = Category::where('name', 'MacBooks')->first();
        $android = Category::where('name', 'Android Phones')->first();
        $laptops = Category::where('name', 'Laptops')->first();

        $products = [
            // iPhones
            [
                'name' => 'iPhone 15 Pro Max',
                'description' => 'The ultimate iPhone experience with titanium design, A17 Pro chip, and advanced camera system.',
                'short_description' => 'Premium iPhone with titanium design and A17 Pro chip',
                'price' => 1199.00,
                'sale_price' => 1099.00,
                'sku' => 'IPH15PM-256',
                'stock_quantity' => 50,
                'brand' => 'Apple',
                'model' => 'iPhone 15 Pro Max',
                'category_id' => $iphones->id,
                'specifications' => [
                    'Display' => '6.7-inch Super Retina XDR',
                    'Chip' => 'A17 Pro',
                    'Storage' => '256GB',
                    'Camera' => '48MP Main, 12MP Ultra Wide, 12MP Telephoto',
                    'Battery' => 'Up to 29 hours video playback',
                    'Colors' => ['Natural Titanium', 'Blue Titanium', 'White Titanium', 'Black Titanium']
                ],
                'images' => ['iphone15promax-1.jpg', 'iphone15promax-2.jpg'],
                'is_active' => true,
            ],
            [
                'name' => 'iPhone 14',
                'description' => 'A great iPhone with advanced dual-camera system and A15 Bionic chip.',
                'short_description' => 'Advanced dual-camera system with A15 Bionic',
                'price' => 799.00,
                'sku' => 'IPH14-128',
                'stock_quantity' => 75,
                'brand' => 'Apple',
                'model' => 'iPhone 14',
                'category_id' => $iphones->id,
                'specifications' => [
                    'Display' => '6.1-inch Super Retina XDR',
                    'Chip' => 'A15 Bionic',
                    'Storage' => '128GB',
                    'Camera' => '12MP Main, 12MP Ultra Wide',
                    'Battery' => 'Up to 20 hours video playback',
                    'Colors' => ['Blue', 'Purple', 'Midnight', 'Starlight', 'Red']
                ],
                'images' => ['iphone14-1.jpg', 'iphone14-2.jpg'],
                'is_active' => true,
            ],

            // MacBooks
            [
                'name' => 'MacBook Pro 16-inch M3 Max',
                'description' => 'The most powerful MacBook Pro ever with M3 Max chip, perfect for professionals.',
                'short_description' => 'Most powerful MacBook Pro with M3 Max chip',
                'price' => 3999.00,
                'sale_price' => 3799.00,
                'sku' => 'MBP16-M3MAX-1TB',
                'stock_quantity' => 25,
                'brand' => 'Apple',
                'model' => 'MacBook Pro 16-inch',
                'category_id' => $macbooks->id,
                'specifications' => [
                    'Display' => '16.2-inch Liquid Retina XDR',
                    'Chip' => 'Apple M3 Max',
                    'Memory' => '36GB unified memory',
                    'Storage' => '1TB SSD',
                    'Battery' => 'Up to 22 hours',
                    'Ports' => '3 x Thunderbolt 4, HDMI, SDXC, MagSafe 3'
                ],
                'images' => ['macbookpro16-1.jpg', 'macbookpro16-2.jpg'],
                'is_active' => true,
            ],
            [
                'name' => 'MacBook Air M2',
                'description' => 'Strikingly thin and fast MacBook Air with M2 chip in a durable all-aluminum enclosure.',
                'short_description' => 'Thin and fast with M2 chip',
                'price' => 1199.00,
                'sku' => 'MBA13-M2-256',
                'stock_quantity' => 40,
                'brand' => 'Apple',
                'model' => 'MacBook Air 13-inch',
                'category_id' => $macbooks->id,
                'specifications' => [
                    'Display' => '13.6-inch Liquid Retina',
                    'Chip' => 'Apple M2',
                    'Memory' => '8GB unified memory',
                    'Storage' => '256GB SSD',
                    'Battery' => 'Up to 18 hours',
                    'Colors' => ['Space Gray', 'Silver', 'Starlight', 'Midnight']
                ],
                'images' => ['macbookair-1.jpg', 'macbookair-2.jpg'],
                'is_active' => true,
            ],

            // Android Phones
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'The ultimate Android flagship with S Pen, advanced AI features, and 200MP camera.',
                'short_description' => 'Ultimate Android flagship with S Pen and AI',
                'price' => 1299.00,
                'sale_price' => 1199.00,
                'sku' => 'SGS24U-256',
                'stock_quantity' => 35,
                'brand' => 'Samsung',
                'model' => 'Galaxy S24 Ultra',
                'category_id' => $android->id,
                'specifications' => [
                    'Display' => '6.8-inch Dynamic AMOLED 2X',
                    'Processor' => 'Snapdragon 8 Gen 3',
                    'RAM' => '12GB',
                    'Storage' => '256GB',
                    'Camera' => '200MP Main, 50MP Periscope, 10MP Telephoto, 12MP Ultra Wide',
                    'Battery' => '5000mAh with 45W fast charging'
                ],
                'images' => ['galaxys24ultra-1.jpg', 'galaxys24ultra-2.jpg'],
                'is_active' => true,
            ],
            [
                'name' => 'Google Pixel 8 Pro',
                'description' => 'Google\'s most advanced Pixel with Magic Eraser, Best Take, and pure Android experience.',
                'short_description' => 'Advanced Pixel with Magic Eraser and pure Android',
                'price' => 999.00,
                'sku' => 'GP8P-128',
                'stock_quantity' => 30,
                'brand' => 'Google',
                'model' => 'Pixel 8 Pro',
                'category_id' => $android->id,
                'specifications' => [
                    'Display' => '6.7-inch LTPO OLED',
                    'Processor' => 'Google Tensor G3',
                    'RAM' => '12GB',
                    'Storage' => '128GB',
                    'Camera' => '50MP Main, 48MP Ultra Wide, 48MP Telephoto',
                    'Battery' => '5050mAh with 30W fast charging'
                ],
                'images' => ['pixel8pro-1.jpg', 'pixel8pro-2.jpg'],
                'is_active' => true,
            ],

            // Laptops
            [
                'name' => 'Dell XPS 13 Plus',
                'description' => 'Premium ultrabook with 12th Gen Intel Core processors and stunning InfinityEdge display.',
                'short_description' => 'Premium ultrabook with 12th Gen Intel Core',
                'price' => 1399.00,
                'sku' => 'DXPS13P-512',
                'stock_quantity' => 20,
                'brand' => 'Dell',
                'model' => 'XPS 13 Plus',
                'category_id' => $laptops->id,
                'specifications' => [
                    'Display' => '13.4-inch InfinityEdge',
                    'Processor' => 'Intel Core i7-1260P',
                    'RAM' => '16GB LPDDR5',
                    'Storage' => '512GB SSD',
                    'Graphics' => 'Intel Iris Xe',
                    'Battery' => 'Up to 12 hours'
                ],
                'images' => ['dellxps13-1.jpg', 'dellxps13-2.jpg'],
                'is_active' => true,
            ],
            [
                'name' => 'HP Spectre x360 14',
                'description' => '2-in-1 convertible laptop with OLED display and premium build quality.',
                'short_description' => '2-in-1 convertible with OLED display',
                'price' => 1599.00,
                'sale_price' => 1399.00,
                'sku' => 'HPS360-14-1TB',
                'stock_quantity' => 15,
                'brand' => 'HP',
                'model' => 'Spectre x360 14',
                'category_id' => $laptops->id,
                'specifications' => [
                    'Display' => '13.5-inch OLED touchscreen',
                    'Processor' => 'Intel Core i7-1255U',
                    'RAM' => '16GB LPDDR4x',
                    'Storage' => '1TB SSD',
                    'Graphics' => 'Intel Iris Xe',
                    'Battery' => 'Up to 17 hours'
                ],
                'images' => ['hpspectre-1.jpg', 'hpspectre-2.jpg'],
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['sku' => $product['sku']], // Search by SKU (unique identifier)
                $product // Data to insert if not found
            );
        }
    }
}
