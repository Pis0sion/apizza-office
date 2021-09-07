<?php


namespace Pis0sion\Docx\categories\eolinker;


use Pis0sion\Docx\layer\IParserInterface;

/**
 * Class ApisEolinkerParser
 * @package Pis0sion\Docx\categories\eolinker
 */
class ApisEolinkerParser implements IParserInterface
{
    /**
     * @param array $eolinkerArr
     * @return array
     */
    public function parse2RenderDocx(array $eolinkerArr): array
    {
        // TODO: Implement parse2RenderDocx() method.
        return $this->organizeProjectVars2Specifications($this->arrange2ClassifyApis($eolinkerArr));
    }

    /**
     * 整理数据到特定规范
     * @param array $projectVars
     * @return array
     */
    protected function organizeProjectVars2Specifications(array $projectVars): array
    {
        foreach ($projectVars as &$projectVar) {
            $moduleApis = &$projectVar['module_list'];
            foreach ($moduleApis as &$moduleApi) {
                $moduleApi['name'] = $moduleApi['baseInfo']['apiName'];
                $moduleApi['debug'] = "Eolinker";
                $moduleApi['request'] = [
                    'api_url' => $moduleApi['baseInfo']['apiURI'],
                    'method' => $this->obtainRequestMethodFromMapping($moduleApi['baseInfo']['apiRequestType']),
                    'contentType' => $this->obtainRequestContentTypeFromMapping($moduleApi['baseInfo']['apiRequestParamType']),
                    'description' => ApiDefaultDescription,
                    'parameters' => $this->organizeDatum2Kv($this->processRequestParameters($moduleApi, "requestInfo")),
                    'raws' => "",
                ];
                $moduleApi['response'] = [
                    'body' => $this->organizeDatum2Kv($this->processRequestParameters($moduleApi, "resultInfo")),
                    'raw' => $this->prettyJson($moduleApi['baseInfo']['apiSuccessMock']),
                ];
            }
        }
        return $projectVars;
    }

    /**
     * 整理eolinker中的数据
     * @param array $eolinkerArr
     * @return array
     */
    protected function arrange2ClassifyApis(array $eolinkerArr): array
    {
        $eolinkerApis = $eolinkerArr['apiGroupList'];
        $projectVars = [];
        // 对数据进行分类
        foreach ($eolinkerApis as $eolinkerApi) {
            // 判断是否为模块
            if (!array_key_exists("apiGroupChildList", $eolinkerApi)) {
                $projectVars[] = [
                    // 分组名称
                    'module_name' => $eolinkerApi['groupName'],
                    'module_list' => $eolinkerApi['apiList'],
                ];
                continue;
            }
            // 存在子类
            $projectVars[] = [
                'module_name' => $eolinkerApi['groupName'] ?? ProjectDefaultList,
                // 获取该模块的所有接口列表
                'module_list' => $this->multiLevelAcquisition($eolinkerApi),
            ];
        }
        return $projectVars;
    }

    /**
     * 获取多层级接口
     * @param array $apiVars
     * @return array
     */
    protected function multiLevelAcquisition(array $apiVars): array
    {
        $module_list = $apiVars['apiList'];
        foreach ($apiVars['apiGroupChildList'] as $apiVar) {
            if (!array_key_exists('apiGroupChildList', $apiVar)) {
                $module_list = array_merge($module_list, $apiVar['apiList']);
                continue;
            }
            $module_list = $this->multiLevelAcquisition($apiVar);
        }
        return $module_list;
    }

    /**
     * 处理成kv类型
     * @param array $isProcessReqParameters
     * @return array
     */
    protected function organizeDatum2Kv(array $isProcessReqParameters): array
    {
        $kvResult = [];
        foreach ($isProcessReqParameters as $processReqParameter) {
            $kvResult[] = [
                'key' => $processReqParameter['paramKey'],
                'type' => $this->obtainParamTypeFromMapping($processReqParameter['paramType']),
                'bool' => $processReqParameter['paramNotNull'] == 0 ? '必填' : '选填',
                'description' => $processReqParameter['paramName'],
            ];
        }
        return $kvResult;
    }

    /**
     * 处理请求参数方法
     * @param array $requestParameters
     * @param string $field
     * @return array
     */
    protected function processRequestParameters(array $requestParameters, string $field): array
    {
        $arrangeArr = [];
        // 存在请求参数
        if (array_key_exists($field, $requestParameters) && count($requestParameters[$field]) > 0) {
            foreach ($requestParameters[$field] as $requestParameter) {
                // 处理childList的结果
                if (isset($requestParameter['childList']) && count($requestParameter['childList']) > 0) {
                    $arrangeArr = $this->recursive2HandleSubclasses($requestParameter, null);
                    continue;
                }
                $arrangeArr[] = $requestParameter;
            }
        }
        return $arrangeArr;
    }

    /**
     * 递归处理子类的函数
     * @param array $requestClasses
     * @param null|string $superior
     * @return array
     */
    protected function recursive2HandleSubclasses(array $requestClasses, ?string $superior): array
    {
        $validRequests = [];
        // 判断是否存在 childList
        $childrenList = $requestClasses['childList'] ?? [];
        $requestClasses['childList'] = [];
        if (!is_null($superior)) {
            // 添加上级信息
            $requestClasses['paramKey'] = $superior . "." . $requestClasses['paramKey'];
        }
        $validRequests[] = $requestClasses;

        foreach ($childrenList as $childList) {
            if (isset($childList['childList']) && count($childList['childList']) == 0) {
                $validRequests[] = $childList;
                continue;
            }
            $validRequests = array_merge($validRequests, $this->recursive2HandleSubclasses($childList, $requestClasses['paramKey']));
        }
        return $validRequests;
    }

    /**
     * 美化json
     * @param string $handleJson
     * @return string
     */
    protected function prettyJson(string $handleJson): string
    {
        $jsonString = json_decode($handleJson, true);
        if (json_last_error() == JSON_ERROR_NONE) {
            return json_encode($jsonString, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        return $handleJson;
    }

    /**
     * @param int $contentType
     * @return string
     */
    protected function obtainRequestContentTypeFromMapping(int $contentType): string
    {
        $defaultContentType = EolinkerContentTypeMapping[0];
        if (array_key_exists($contentType, EolinkerContentTypeMapping)) {
            $defaultContentType = EolinkerContentTypeMapping[$contentType];
        }
        return $defaultContentType;
    }

    /**
     * 获取请求方法
     * @param int $methodType
     * @return string
     */
    protected function obtainRequestMethodFromMapping(int $methodType): string
    {
        $defaultMethod = 'post';
        if (array_key_exists($methodType, EolinkerMethodsMapping)) {
            $defaultMethod = EolinkerMethodsMapping[$methodType];
        }
        return $defaultMethod;
    }

    /**
     * 获取参数类型
     * @param int $paramType
     * @return string
     */
    protected function obtainParamTypeFromMapping(int $paramType): string
    {
        $defaultType = 'string';
        if (array_key_exists($paramType, EolinkerParamTypeMapping)) {
            $defaultType = EolinkerParamTypeMapping[$paramType];
        }
        return $defaultType;
    }
}