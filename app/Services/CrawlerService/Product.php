<?php

namespace App\Services\CrawlerService;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

class Product extends Http
{

    /**
     * @var string
     */
    private $zipCode;

    /**
     * Product constructor.
     * @param $zipCode
     */
    public function __construct($zipCode)
    {
        parent::__construct();

        $this->zipCode = $zipCode;

        if (!Cache::has($this->getCacheKey())) {
            $this->setZipCode();
        } else {
            $this->setZipCodeByCache();
        }
    }

    /**
     * Get all products
     *
     * @return array
     * @throws \Exception
     */
    public function getAllProducts()
    {

        $categorys = [
            'organic', 'produce', 'dairyandeggs','meatandfish','preparedfoods','snacks','drinks','pantry','bakery',
            'vegan','nongmo','glutenfree','homegoods','healthandbeauty','babyandkids','paleo','other'
        ];

        shuffle($categorys);

        $products = [];

        foreach ($categorys as $category) {

            $products = array_merge($products, $this->getProducts($category));

            sleep(rand(5, 10)); // rand sleep

        }

        return $products;
    }

    /**
     * Get products
     *
     * @param $category
     * @return array
     * @throws \Exception
     */
    public function getProducts($category)
    {
        try {
            $response = $this->get("https://www.grubmarket.com/home?category={$category}");

            $content = (string)$response->getBody()->getContents();

            $this->saveHtmlFile("{$this->zipCode}_{$category}", $content);

            $products = $this->parseProducts($content);

            $products = array_map(function ($product) use ($category) {
                $product['category'] = $category;
                $product['zip_code'] = $this->zipCode;
                return $product;
            }, $products);

            Cache::forever($this->getCacheKey(), $this->getCookieJar()->toArray());

            return $products;

        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                Log::info("{$this->zipCode} {$category} 404 Not Found");
                return [];
            } else {
                Log::error($e->getMessage(), $e->getTrace());
                throw new \Exception($e->getMessage());
            }
        }
    }

    /**
     * Parse products
     *
     * @param $contents
     * @return array
     */
    private function parseProducts($contents)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($contents);
        $products = [];
        $crawler->filter('#product-list-section > ul > div > div.product-media-list')->children()->each(function (Crawler $node) use (&$products) {
            $products[] = Parser::product($node);
        });

        return $products;
    }

    private function setZipCode()
    {
        $this->get('https://tuangou.grubmarket.com');

        $request = $this->post('https://tuangou.grubmarket.com/api/region_deals/zipcode/',$this->zipCode);

        Cache::forever($this->getCacheKey(), $this->getCookieJar()->toArray());

    }

    /**
     * Set zip code by cache
     */
    private function setZipCodeByCache()
    {
        $cookies = Cache::get($this->getCacheKey());
        $cookieJar = new CookieJar();
        foreach ($cookies as $cookie) {
            $setCookie = new SetCookie($cookie);
            $cookieJar->setCookie($setCookie);
        }
        $this->setCookieJar($cookieJar);
    }

    /**
     * Get cache key
     *
     * @return string
     */
    private function GetCachekey(){

        return "weee_cookie_{$this->zipCode}";

    }

    /**
     * Save html file
     *
     * @param $fileName
     * @param $contents
     */
    private function saveHtmlFile($fileName, $contents)
    {
        $fileName = preg_replace('/[^a-z0-9]+/', '-', strtolower($fileName));
        $filePath = date('Y-m-d') . '/' . $fileName . '.html';
        Storage::disk('html')->put($filePath, $contents);
    }


}


