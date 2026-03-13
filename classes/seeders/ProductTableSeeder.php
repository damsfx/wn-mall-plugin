<?php

namespace Winter\Mall\Classes\Seeders;

use Winter\Mall\Models\Product;
use Winter\Mall\Models\ProductPrice;
use Winter\Mall\Models\Variant;
use Winter\Storm\Database\Updates\Seeder;

class ProductTableSeeder extends Seeder
{
    public function run()
    {
        if (app()->environment() === 'testing') {
            try {
                Product::extend(function () {
                    $this->setTable('offline_mall_products');
                }, true);
                ProductPrice::extend(function () {
                    $this->setTable('offline_mall_product_prices');
                }, true);
                Variant::extend(function () {
                    $this->setTable('offline_mall_product_variants');
                }, true);

                $product       = new Product();
                $product->name = 'Test';

                $product->slug  = 'test';
                $product->stock = 20;
                $product->save();
                $product->price = ['CHF' => 20, 'EUR' => 30];

                $product       = new Product();
                $product->name = 'Test 2';

                $product->slug  = 'test-2';
                $product->stock = 90;
                $product->save();
                $product->price = ['CHF' => 30, 'EUR' => 40];
                
                Variant::extend(function () {
                    $this->setTable('winter_mall_product_variants');
                }, true);
                ProductPrice::extend(function () {
                    $this->setTable('winter_mall_product_prices');
                }, true);
                Product::extend(function () {
                    $this->setTable('winter_mall_products');
                }, true);
            } catch (\Throwable $e) {
                dd($e);
            }
        }
    }
}
