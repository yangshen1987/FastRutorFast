<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-7-18
 * Time: 下午6:43
 */

namespace we7\HttpRoute\RouteParser;


use we7\HttpRoute\RouteBase;

class GetRouteData extends RouteBase
{
    protected function getApproxChunkSize()
    {
        return 10;
    }

    protected function processChunk($regexToRoutesMap)
    {
        $routeMap = [];
        $regexes = [];
        $numGroups = 0;
        foreach ($regexToRoutesMap as $regex => $route) {
            $numVariables = count($route['variables']);
            $numGroups = max($numGroups, $numVariables);
            $httpMethod = $route['httpMethod'];
            $handler    = $route['handler'];
            $regexes[] = $regex . str_repeat('()', $numGroups - $numVariables);
            $routeMap[$numGroups + 1] = [$route['handler'], $route['variables']];

            ++$numGroups;
        }

        var_dump($regexToRoutesMap);
        $regex = '~^(?|' . implode('|', $regexes) . ')$~';
        return ['regex' => $regex, 'routeMap' => $routeMap, 'httpMethod'=>$httpMethod, 'handler'=>$handler];
    }
}