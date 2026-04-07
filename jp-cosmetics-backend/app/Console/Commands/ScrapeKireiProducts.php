<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Support\Str;

class ScrapeKireiProducts extends Command
{
    protected $signature = 'scrape:kirei';
    protected $description = 'Scrape products from kireibd.com';

    public function handle()
    {
        $client = new Client();
        $baseUrl = 'https://kireibd.com';

        $response = $client->get($baseUrl . '/shop');

        $crawler = new Crawler($response->getBody()->getContents());

        $productLinks = [];

        // collect product urls
        $crawler->filter('a')->each(function ($node) use (&$productLinks, $baseUrl) {

            $href = $node->attr('href');

            if (Str::contains($href, '/product/')) {
                $productLinks[] = $href;
            }
        });

        $productLinks = array_unique($productLinks);

        foreach ($productLinks as $link) {

        dd($link);
            $this->info("Scraping: " . $link);

            try {

                $html = $client->get($link)->getBody()->getContents();

                $productPage = new Crawler($html);

                $name = $productPage->filter('h1')->first()->text();

                $price = $productPage->filter('.price')->first()->text();

                $description = '';

                if ($productPage->filter('.woocommerce-product-details__short-description')->count()) {
                    $description = $productPage
                        ->filter('.woocommerce-product-details__short-description')
                        ->text();
                }

                $image = null;

                if ($productPage->filter('img')->count()) {
                    $image = $productPage->filter('img')->first()->attr('src');
                }

                $slug = Str::slug($name);

                $product = Product::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'slug' => $slug,
                        'category_id' => 1,
                        'primary_image' => $image,
                        'product_type' => 'single',
                        'status' => 'active',
                        'short_description' => $description,
                        'long_description' => $description
                    ]
                );

                ProductAttribute::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'attribute_name' => 'Default'
                    ],
                    [
                        'attribute_value' => 'Default',
                        'unit_price' => preg_replace('/[^0-9]/', '', $price),
                        'stock' => 100,
                        'is_default' => 1,
                        'status' => 1
                    ]
                );

                sleep(1);

            } catch (\Exception $e) {

                $this->error("Error scraping: " . $link);
            }
        }

        $this->info('Scraping finished.');
    }
}