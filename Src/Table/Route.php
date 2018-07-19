<?php
namespace we7\HttpRoute\Table;
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-7-18
 * Time: 下午6:19
 */
use we7\HttpRoute\Exception\BadRouteException;
use we7\HttpRoute\RouteParser\GetRouteData;
use we7\HttpRoute\RouteParser\Std;
use Swoole\Table;
class Route
{

    public $tableName = "";


    /**
     * @var Table
     */
    public $tableObj;


    /**
     * @var
     */
    public $parasObj;

    /**
     * 创建内存表
     * @return bool|Table
     */
    protected function createMemroyTable()
    {
        $this->tableObj = new Table(2048);
        $this->tableObj->column('httpMethod', Table::TYPE_STRING, 64);
        $this->tableObj->column('values', Table::TYPE_STRING, 1024);
        $result = $this->tableObj->create();
        if($result == false)
        {
            throw new BadRouteException("create memory table wrong");
        }
        return $this->tableObj;
    }

    /**
     * 插入内存表中
     * @param $httpMethod
     * @param $handler
     * @param $regex
     * @param $variables
     */
    protected function setInTable($httpMethod, $handler, $regex, $variables)
    {
        $data  = $this->tableObj->get($httpMethod);
        if(empty($data)){
            $data = [];
        }
        $tmpData['httpMethod'] = $httpMethod;
        $tmpData['regex']      = $regex;
        $tmpData['variables']  = $variables;
        $tmpData['handler']    = $handler;
        $data[] = $tmpData;
        $this->tableObj->set($httpMethod, ['values'=>json_encode($data)]);
    }

    /**
     * 获取内存表中的路由参数
     * @param $httpMethod
     * @return mixed
     */
    public function getTableByHttpMethod($httpMethod)
    {
        $data = $this->tableObj->get($httpMethod);
        if (empty($data)){
            return [];
        }
        return json_decode($data['values'], true);
    }

    /**
     * @param $staticRouteMap
     */
    protected function setVariableRouteData($variableRouteData)
    {
        foreach($variableRouteData as $routeData){
            foreach($routeData as $httpMethod=>$routeDatum) {
                $this->setInTable($routeDatum['httpMethod'], $routeDatum['handler'], $routeDatum['regex'], json_encode($routeDatum['routeMap']));
            }
        }
    }

    /**
     * 添加如路由
     *      * [
     *  "GET"=>[
     *          'handler'=>"/module/info/{id:\d+}"
     *          ]
     * ]
     * @param $httpMethod
     */
    public function addRoute($httpMethod, $routeData)
    {
        $parasObj  = new Std();
        $routeInfo = [];
        foreach($routeData as $handler=>$url){
            $routeInfo[$handler] = $parasObj->parse($url)[0];
        }
        $routeDataObj = new GetRouteData();
        foreach($routeInfo as $handler=>$route) {
            $routeDataObj->addRoute($httpMethod, $route, $handler);
        }
        $routeDatas = $routeDataObj->getData();
        $this->createMemroyTable();
        list($staticRouteMap, $variableRouteData) = $routeDatas;
        if (!empty($staticRouteMap)) {
            $this->setSaticRouteMapIntoMaps($staticRouteMap);
        }
        if (!empty($variableRouteData)) {
            $this->setVariableRouteData($variableRouteData);
        }
    }

    protected function setSaticRouteMapIntoMaps($staticRouteMap)
    {
        foreach($staticRouteMap as $routeData){
            foreach($routeData as $httpMethod=>$routeDatum) {
                $this->setInTable($routeDatum['httpMethod'], $routeDatum['handler'], $routeDatum['regex'], json_encode($routeDatum['routeMap']));
            }
        }
    }
}