<?php

return [

    // 是否开启代理
    'proxy' => env('CRAWLER_PROXY',false),
    // 代理地址
    'proxy_url' => env('CRAWLER_PROXY_URL','127.0.0.1:10808'),

];
