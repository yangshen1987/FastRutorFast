<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-7-18
 * Time: 下午6:43
 */

namespace we7\HttpRoute\RouteParser;


use we7\HttpRoute\RouteBase;

class PostRouteData extends RouteBase
{
    /**
     * @param $regexToRoutesMap
     * @return array
     */
    protected function processChunk($regexToRoutesMap)
    {
        $routeMap = [];
        $regexes = [];
        $numGroups = 0;
        foreach ($regexToRoutesMap as $regex => $route) {
            $numVariables = count($route->variables);
            $numGroups = max($numGroups, $numVariables);

            $regexes[] = $regex . str_repeat('()', $numGroups - $numVariables);
            $routeMap[$numGroups + 1] = [$route->handler, $route->variables];

            ++$numGroups;
        }

        $regex = '~^(?|' . implode('|', $regexes) . ')$~';
        return ['regex' => $regex, 'routeMap' => $routeMap];
    }
}