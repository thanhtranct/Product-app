<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptop Dell XPS 15',
                'price' => 1299.99,
                'description' => 'High-performance laptop with Intel Core i7 processor, 16GB RAM, and 512GB SSD.',
            ],
            [
                'name' => 'iPhone 14 Pro',
                'price' => 999.00,
                'description' => 'Latest iPhone with A16 Bionic chip, Pro camera system, and Always-On display.',
            ],
            [
                'name' => 'Sony WH-1000XM5 Headphones',
                'price' => 399.99,
                'description' => 'Premium noise-cancelling wireless headphones with exceptional sound quality.',
            ],
            [
                'name' => 'Samsung 4K Smart TV',
                'price' => 799.00,
                'description' => '55-inch 4K UHD Smart TV with HDR and built-in streaming apps.',
            ],
            [
                'name' => 'Apple Watch Series 8',
                'price' => 429.00,
                'description' => 'Advanced health and fitness tracking with temperature sensing and crash detection.',
            ],
        ];

        foreach ($products as $productData) {
            // Gọi API để lấy external data
            try {
                $randomId = rand(1, 100);
                $response = Http::get("https://dummyjson.com/products/{$randomId}");
                if ($response->successful()) {
                    $data = $response->json();
                    $productData['external_api_data'] = $data['title'] ?? null;
                }
            } catch (\Exception $e) {
                $productData['external_api_data'] = null;
            }

            Product::create($productData);
        }

        $this->command->info('Products seeded successfully!');
        $this->call([
        ProductSeeder::class,
    ]);
    }
    
}