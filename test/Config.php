<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-7-19
 * Time: 上午11:13
 */

namespace we7\HttpRoute;


return
    [
        "GET"=>[
            'handler1'=>"/module/info/{id:\d+}",
            'handler2'=>"/module/info",
            'handler3'=>"/module/query",
            'handler4'=>"/module/build",
        ],
        "POST"=>[
            'handler1'=>"/module/info/{id:\d+}",
            'handler2'=>"/module/info",
            'handler3'=>"/module/query",
            'handler4'=>"/module/build",
        ],
    ];