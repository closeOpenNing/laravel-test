<?php

namespace App\Console\Commands;

use App\Services\CrawlerService\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MultithreadingRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = 0;

        $date = date('Y-m-d');

        $zip_Codes = ['94124','45219'];

        ding("{$date} 爬虫任务开始");

        try{

            foreach ($zip_Codes as $zipCode){

                $weeeProductCrawler = new Product($zipCode);

                $products = $weeeProductCrawler->getAllProducts();

                $count += count($products);


            }

        } catch (\Exception $exception){

            log::error($exception);

            ding("{$date} 爬虫失败,原因是{$exception->getMessage()}");

        };


    }

}
