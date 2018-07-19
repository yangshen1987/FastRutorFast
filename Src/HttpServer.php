<?php
namespace we7\HttpRoute;

include_once dirname(__FILE__) . '/bootstrap.php';

use we7\HttpRoute\Exception\BadRequestException;
use we7\HttpRoute\Exception\BadRouteException;
use we7\HttpRoute\RouteParser\Dispatcher\GroupCountBased;
use we7\HttpRoute\Table\Route;



/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-7-18
 * Time: 下午2:48
 */

class HttpServer
{
    /**
     * @param array $configData
     * @return bool
     */
    public function addRoutDataInCache(array $configData)
    {
        if (empty($configData)){
            throw new BadRouteException("config is not must be empty");
        }
        $routeObj = new Route();
        foreach($configData as $httpMethod=>$routeData)
        {
            $routeObj->addRoute($httpMethod, $routeData);
        }
        return true;
    }

    /**
     * @param $httpMethod
     * @param $url
     */
    public function dispatch($httpMethod, $url)
    {
        $routeObj   = new Route();
        $routeData  = $routeObj->getTableByHttpMethod($httpMethod);
        $dispatcher = new GroupCountBased($routeData);
        $routeInfo  = $dispatcher->dispatch($httpMethod, $url);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND;
            case Dispatcher::METHOD_NOT_ALLOWED;
            throw new BadRequestException("route is not found");
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $funArgs = $routeInfo[2];
                break;
        }
        return['handler'=>$handler, 'funArgs'=>$funArgs];
    }


}