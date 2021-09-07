<?php


namespace Pis0sion\Docx\categories\apipost;


use Pis0sion\Docx\layer\IParserInterface;

/**
 * Class ApisApiPostParser
 * @package Pis0sion\Docx\categories\apipost
 */
class ApisApiPostParser implements IParserInterface
{
    /**
     * @param array $apipostArr
     * @return array
     */
    public function parse2RenderDocx(array $apipostArr): array
    {
        return $this->organizeProjectVars2Specifications($this->arrange2ClassifyApis($apipostArr));
    }

    /**
     * @param array $arrange2ClassifyApis
     * @return array
     */
    protected function organizeProjectVars2Specifications(array $arrange2ClassifyApis): array
    {
        foreach ($arrange2ClassifyApis as &$arrange2ClassifyApi) {
            $moduleApis = &$arrange2ClassifyApi['module_list'];

            foreach ($moduleApis as &$moduleApi) {
                // 新增字段
                $moduleApi['debug'] = "ApiPost";
                $apiRequest = $moduleApi['request'];
                $moduleApi['request'] = [
                    'api_url' => $apiRequest['url'] ?? ApiDefaultEmptyUrl,
                    'method' => strtolower($moduleApi['method'] ?? "post"),
                    'contentType' => ContentTypeMap[$apiRequest['body']['mode']] ?? DefaultContentType,
                    'description' => trim($apiRequest['description']) != "" ? $apiRequest['description'] : ApiDefaultDescription,
                    // 请求头 参数
                    'headers' => $this->obtainKVRequestParameters($apiRequest, 'header'),
                    // uri  参数
                    'queries' => $this->obtainKVRequestParameters($apiRequest, 'query'),
                    // 路径  参数
                    'restful' => $this->obtainKVRequestParameters($apiRequest, 'resful'),
                    // 前执行脚本
                    'pre_script' => $apiRequest['event']['pre_script'] ?? "",
                    // 后执行脚本
                    'test' => $apiRequest['event']['test'] ?? "",
                    // body 参数
                    'parameters' => $this->obtainKVRequestParameters($apiRequest, 'body'),
                    // 获取请求示例
                    'raws' => $this->obtainRawRequestParameters($apiRequest),
                ];

                // 响应参数
                $apiResponse = $moduleApi['response'];
                $moduleApi['response'] = [
                    // 响应的结果
                    'body' => $this->obtainKVResponseParameters($apiResponse, 'success'),
                    // 响应的原生结果
                    'raw' => $this->obtainRawRequestParameters($apiResponse, 'success'),
                ];
            }
        }

        return $arrange2ClassifyApis;
    }

    /**
     * @param array $apipostArr
     * @return array
     */
    protected function arrange2ClassifyApis(array $apipostArr): array
    {
        // 获取接口数据
        $apipostApis = $apipostArr['children'];
        $projectVars = [];
        $module_name = ProjectDefaultList;
        // 默认列表数据
        $module_list = [];
        // 对数据进行分类
        foreach ($apipostApis as $apipostApi) {
            // 判断是否为模块
            if (array_key_exists("children", $apipostApi)) {
                $projectVars[] = [
                    'module_name' => $apipostApi['name'],
                    'module_list' => $this->multiLevelAcquisition($apipostApi['children']),
                ];
                continue;
            }
            if ($apipostApi['target_type'] != "folder") {
                // 模块列表
                $module_list[] = $apipostApi;
            }
        }
        // 添加默认项目列表模块
        if (count($module_list) != 0) {
            array_unshift($projectVars, compact('module_name', 'module_list'));
        }

        return $projectVars;
    }

    /**
     * 获取多层级接口
     * @param $apiVars
     * @return array
     */
    protected function multiLevelAcquisition(array $apiVars): array
    {
        $module_list = [];
        foreach ($apiVars as $apiVar) {
            // 不存在children
            if (!array_key_exists('children', $apiVar) && ($apiVar['target_type'] != "folder")) {
                $module_list[] = $apiVar;
                continue;
            }
            if (array_key_exists('children', $apiVar)) {
                $module_list = array_merge($module_list, $this->multiLevelAcquisition($apiVar['children']));
            }
        }

        return $module_list;
    }

    /**
     * 处理成kv类型
     * @param array $apiRequest
     * @param string $field
     * @return array
     */
    protected function obtainKVRequestParameters(array $apiRequest, string $field)
    {
        $isProcessReqParameters = $apiRequest[$field]['parameter'] ?? [];
        $kvResult = [];
        foreach ($isProcessReqParameters as $processReqParameter) {
            $kvResult[] = [
                'key' => $processReqParameter['key'],
                'value' => $processReqParameter['value'],
                'bool' => ($processReqParameter['field_type'] ?? "String") ? $processReqParameter['field_type'] : 'String',
                'description' => trim($processReqParameter['description']) != '' ? trim($processReqParameter['description']) : "暂无描述",
            ];
        }
        return $kvResult;
    }

    /**
     * 处理成kv类型
     * @param array $apiRequest
     * @param string $field
     * @return array
     */
    protected function obtainKVResponseParameters(array $apiRequest, string $field)
    {
        $isProcessReqParameters = $apiRequest[$field]['parameter'] ?? [];
        $kvResult = [];
        foreach ($isProcessReqParameters as $processReqParameter) {
            $kvResult[] = [
                'key' => $processReqParameter['key'],
                'value' => $processReqParameter['value'],
                'bool' => ($processReqParameter['field_type'] ?? "String") ? $processReqParameter['field_type'] : 'String',
                'description' => trim($processReqParameter['description']) != '' ? trim($processReqParameter['description']) : "暂无描述",
            ];
        }
        return $kvResult;
    }

    /**
     * 提取 raw 数据
     * @param array $apiVars
     * @param string|null $fields
     * @return string
     */
    protected function obtainRawRequestParameters(array $apiVars, string $fields = null): string
    {
        if ($fields == null) {
            return $apiVars['body']['raw'] ?? "";
        }
        return $apiVars[$fields]['raw'] ?? "";
    }

}