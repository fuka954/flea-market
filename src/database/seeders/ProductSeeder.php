<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => '腕時計',
                'brand' => 'ブランド1',
                'description' => 'スタイリッシュなデザインのメンズ腕時計', 
                'image' => 'Armani+Mens+Clock.jpg',
                'condition' => '良好',
                'price' => '15000',
                'categories' => ['ファッション','メンズ']
            ],
            [
                'name' => 'HDD',
                'brand' => 'ブランド2',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
                'price' => '5000',
                'categories' => ['家電','ゲーム']
            ],
            [
                'name' => '玉ねぎ3束',
                'brand' => 'ブランド3',
                'description' => '新鮮な玉ねぎ3束のセット', 
                'image' => 'iLoveIMG+d.jpg',
                'condition' => 'やや傷や汚れあり',
                'price' => '300',
                'categories' => ['インテリア','キッチン']
            ],
            [
                'name' => '革靴',
                'brand' => 'ブランド4',
                'description' => 'クラシックなデザインの革靴', 
                'image' => 'Leather+Shoes+Product+Photo.jpg',
                'condition' => '状態が悪い',
                'price' => '4000',
                'categories' => ['ファッション','メンズ']
            ],
            [
                'name' => 'ノートPC',
                'brand' => 'ブランド5',
                'description' => '高性能なノートパソコン', 
                'image' => 'Living+Room+Laptop.jpg',
                'condition' => '良好',
                'price' => '45000',
                'categories' => ['家電','ゲーム']
            ],
            [
                'name' => 'マイク',
                'brand' => 'ブランド6',
                'description' => '高音質のレコーディング用マイク', 
                'image' => 'Music+Mic+4632231.jpg',
                'condition' => '目立った傷や汚れなし',
                'price' => '8000',
                'categories' => ['家電','ゲーム']
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => 'ブランド7',
                'description' => 'おしゃれなショルダーバッグ', 
                'image' => 'Purse+fashion+pocket.jpg',
                'condition' => 'やや傷や汚れあり',
                'price' => '15000',
                'categories' => ['ファッション','レディース']
            ],
            [
                'name' => 'タンブラー',
                'brand' => 'ブランド8',
                'description' => '使いやすいタンブラー', 
                'image' => 'Tumbler+souvenir.jpg',
                'condition' => '状態が悪い',
                'price' => '500',
                'categories' => ['レディース','メンズ','キッチン']
            ],
            [
                'name' => 'コーヒーミル',
                'brand' => 'ブランド9',
                'description' => '手動のコーヒーミル', 
                'image' => 'Waitress+with+Coffee+Grinder.jpg',
                'condition' => '良好',
                'price' => '4000',
                'categories' => ['インテリア','キッチン']
            ],
            [
                'name' => 'メイクセット',
                'brand' => 'ブランド10',
                'description' => '便利なメイクアップセット', 
                'image' => '外出メイクアップセット.jpg',
                'condition' => '目立った傷や汚れなし',
                'price' => '2500',
                'categories' => ['ファッション','レディース','コスメ']
            ],
        ];

        foreach ($products as $productData) {
            $sourcePath = public_path('seeder_images/' . $productData['image']);
            $destinationPath = 'public/images/product' . $productData['image'];

            if (file_exists($sourcePath)) {
                Storage::putFileAs('public/images/product', new \Illuminate\Http\File($sourcePath), $productData['image']);
            }

            $condition = Condition::where('condition', $productData['condition'])->first();
            $conditionId = $condition->id;

            $soldFlag = rand(0, 1);
            $sellUser = rand(1,10);
            $buyUser = $soldFlag === 1 ? rand(1, 10) : null;

            $product = Product::create([
                'name' => $productData['name'],
                'brand' => $productData['brand'],
                'description' => $productData['description'],
                'image' => 'images/product/' . $productData['image'],
                'condition_id' => $conditionId,
                'price' => $productData['price'],
                'sold_flag' => $soldFlag,
                'sell_user' => $sellUser,
                'buy_user' => $buyUser,
            ]);

            foreach ($productData['categories'] as $categoryName) {
                $category = Category::where('category', $categoryName)->first();
                if ($category) {
                    $product->categories()->attach($category->id);
                }
            }
        }
    }
}
