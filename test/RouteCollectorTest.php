<?php

namespace HttpRoute;

use PHPUnit\Framework\TestCase;
use Swoole\Http\Server;
use we7\HttpRoute\HttpServer;

class RouteCollectorTest extends TestCase
{
    /**
     * @dataProvider addRouteData
     * @param $routeConfig
     */
    public function testShortcuts($routeConfig, $httpMethodArg)
    {
        $newRouteConfig[$httpMethodArg] = $routeConfig;
        $r = new HttpServer();
        $r->addRoutDataInCache($newRouteConfig);
        $httpMethod = "GET";
        $url        = "/module/info/1";
        $array = $r->dispatch($httpMethod, $url);
        $this->assertEquals(['handler'=>"handler1", "funArgs"=>1], $array);
    }
    public function addRouteData()
    {
        return [
            [
                "GET"=>[
                    'handler1'=>"/module/info/{id:\d+}",
                    'handler2'=>"/module/info",
                    'handler3'=>"/module/query",
                    'handler4'=>"/module/build",
                ],
                'GET',
            ],
//            [
//                "POST"=>[
//                    'handler1'=>"/module/info/{id:\d+}",
//                    'handler2'=>"/module/info",
//                    'handler3'=>"/module/query",
//                    'handler4'=>"/module/build",
//                ],
//                "POST",
//            ],
        ];
    }

//    public function testGroups()
//    {
//        $r = new DummyRouteCollector();
//
//        $r->delete('/delete', 'delete');
//        $r->get('/get', 'get');
//        $r->head('/head', 'head');
//        $r->patch('/patch', 'patch');
//        $r->post('/post', 'post');
//        $r->put('/put', 'put');
//        $r->options('/options', 'options');
//
//        $r->addGroup('/group-one', function (DummyRouteCollector $r) {
//            $r->delete('/delete', 'delete');
//            $r->get('/get', 'get');
//            $r->head('/head', 'head');
//            $r->patch('/patch', 'patch');
//            $r->post('/post', 'post');
//            $r->put('/put', 'put');
//            $r->options('/options', 'options');
//
//            $r->addGroup('/group-two', function (DummyRouteCollector $r) {
//                $r->delete('/delete', 'delete');
//                $r->get('/get', 'get');
//                $r->head('/head', 'head');
//                $r->patch('/patch', 'patch');
//                $r->post('/post', 'post');
//                $r->put('/put', 'put');
//                $r->options('/options', 'options');
//            });
//        });
//
//        $r->addGroup('/admin', function (DummyRouteCollector $r) {
//            $r->get('-some-info', 'admin-some-info');
//        });
//        $r->addGroup('/admin-', function (DummyRouteCollector $r) {
//            $r->get('more-info', 'admin-more-info');
//        });
//
//        $expected = [
//            ['DELETE', '/delete', 'delete'],
//            ['GET', '/get', 'get'],
//            ['HEAD', '/head', 'head'],
//            ['PATCH', '/patch', 'patch'],
//            ['POST', '/post', 'post'],
//            ['PUT', '/put', 'put'],
//            ['OPTIONS', '/options', 'options'],
//            ['DELETE', '/group-one/delete', 'delete'],
//            ['GET', '/group-one/get', 'get'],
//            ['HEAD', '/group-one/head', 'head'],
//            ['PATCH', '/group-one/patch', 'patch'],
//            ['POST', '/group-one/post', 'post'],
//            ['PUT', '/group-one/put', 'put'],
//            ['OPTIONS', '/group-one/options', 'options'],
//            ['DELETE', '/group-one/group-two/delete', 'delete'],
//            ['GET', '/group-one/group-two/get', 'get'],
//            ['HEAD', '/group-one/group-two/head', 'head'],
//            ['PATCH', '/group-one/group-two/patch', 'patch'],
//            ['POST', '/group-one/group-two/post', 'post'],
//            ['PUT', '/group-one/group-two/put', 'put'],
//            ['OPTIONS', '/group-one/group-two/options', 'options'],
//            ['GET', '/admin-some-info', 'admin-some-info'],
//            ['GET', '/admin-more-info', 'admin-more-info'],
//        ];
//
//        $this->assertSame($expected, $r->routes);
//    }
}

//class DummyRouteCollector extends RouteCollector
//{
//    public $routes = [];
//
//    public function __construct()
//    {
//    }
//
//    public function addRoute($method, $route, $handler)
//    {
//        $route = $this->currentGroupPrefix . $route;
//        $this->routes[] = [$method, $route, $handler];
//    }
//}
