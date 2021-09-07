<?php


namespace Pis0sion\Docx\categories\postman;

use Pis0sion\Docx\layer\IParserInterface;
use stdClass;

/**
 * Class ApisPostManParser
 * @package Pis0sion\Docx\categories\postman
 */
class ApisPostManParser implements IParserInterface
{
    /**
     * 解析字段并且生成文档
     * @param array $postmanArr
     * @return array
     */
    public function parse2RenderDocx(array $postmanArr): array
    {
        return $this->organizeProjectVars2Specifications($this->arrange2ClassifyApis($postmanArr));
    }

    /**
     * 编排接口数据并且分类
     * @param array $postmanArr
     * @return array
     */
    protected function arrange2ClassifyApis(array $postmanArr): array
    {
        // 获取接口数据
        $postmanApis = $postmanArr['item'];
        $projectVars = [];
        $module_name = ProjectDefaultList;
        $module_list = [];
        // 对数据进行分类
        foreach ($postmanApis as $postmanApi) {
            // 判断是否为模块
            if (array_key_exists("item", $postmanApi)) {
                $projectVars[] = [
                    'module_name' => $postmanApi['name'],
                    'module_list' => $this->multiLevelAcquisition($postmanApi['item']),
                ];
                continue;
            }
            // 模块列表
            $module_list[] = $postmanApi;
        }
        // 添加默认项目列表模块
        if (count($module_list) != 0) {
            array_unshift($projectVars, compact('module_name', 'module_list'));
        }

        return $projectVars;
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
                // 新增字段
                $apiRequest = &$moduleApi['request'];
                $apiRequest['api_url'] = $moduleApi['request']['url']['raw'] ?? ApiDefaultEmptyUrl;
                $apiRequest['contentType'] = $this->obtainContentType2ApiVars($apiRequest);
                $apiRequest['description'] = $apiRequest['description'] ?? ApiDefaultDescription;
                $apiRequest['parameters'] = $this->obtainKVRequestParameters($apiRequest);
                $apiRequest['raws'] = $this->obtainRawRequestParameters($apiRequest);

                $apiResponse = &$moduleApi['response'];
                $apiResponse['body'] = [];
                $apiResponse['raw'] = SuccessPostManRaw;
                if (count($moduleApi['response']) == true) {
                    $apiResponseArr = $this->obtainResponse2Success($moduleApi['response']);
                    $apiResponse['body'] = $apiResponseArr['body'] ?? [];
                    $apiResponse['raw'] = $apiResponseArr['raw'] ?? SuccessPostManRaw;
                }
            }
        }
        return $projectVars;
    }

    /**
     * 获取成功的响应数据并且整理
     * @param array $successResponseArr
     * @return array
     */
    protected function obtainResponse2Success(array $successResponseArr): array
    {
        $successRespond = [];
        foreach ($successResponseArr as $successResponse) {
            if (isset($successResponse['code']) && $successResponse['code'] == SuccessPostManCode) {
                $successRespond['raw'] = $successResponse['body'] ?? SuccessPostManRaw;
                // 根据 raw 获取body
                $obtainDatum = json_decode($successRespond['raw'], true);
                if (json_last_error() == JSON_ERROR_NONE) {
                    $successRespond['body'] = $this->organizeDatum2Kv($this->recursionArr($obtainDatum, null));
                }
                break;
            }
        }
        return $successRespond;
    }

    /**
     * 递归 postman json 获取字段的数据结构
     * @param array $arrDatum
     * @param string|null $keyString
     * @return array
     */
    protected function recursionArr(array $arrDatum, ?string $keyString): array
    {
        $re = [];
        foreach ($arrDatum as $key => $value) {
            $handleKey = $key;
            if (!empty($keyString)) {
                if (!is_int($key)) {
                    $handleKey = $keyString . "." . $handleKey;
                } else {
                    $handleKey = $keyString;
                }
            }

            if (is_array($value)) {
                $re[$handleKey] = new stdClass();
                $re = array_merge($re, $this->recursionArr($value, $handleKey));
                continue;
            }

            // TODO：这里面需要区分 array object
            if (is_int($key) && !empty($keyString)) {
                $value = [];
            }

            $re[$handleKey] = $value;
        }
        return $re;
    }

    /**
     * 整理数据成kv
     * @param array $inputArr
     * @return array
     */
    protected function organizeDatum2Kv(array $inputArr): array
    {
        $retResult = [];
        foreach ($inputArr as $key => $input) {
            $ret['key'] = $key;
            if (is_object($input)) {
                $ret['value'] = "Object";
            } elseif (is_array($input)) {
                $ret['value'] = "Array";
            } else {
                $ret['value'] = $input;
            }
            $ret['type'] = gettype($input);
            $ret['description'] = EmptyDescriptions;
            $retResult[] = $ret;
        }
        return $retResult;
    }

    /**
     * 获取多层级接口
     * @param array $apiVars
     * @return array
     */
    protected function multiLevelAcquisition(array $apiVars): array
    {
        $module_list = [];
        foreach ($apiVars as $apiVar) {
            if (!array_key_exists('item', $apiVar)) {
                $module_list[] = $apiVar;
                continue;
            }
            $module_list = $this->multiLevelAcquisition($apiVar['item']);
        }
        return $module_list;
    }

    /**
     * 获取contentType类型
     * @param array $apiVars
     * @return string
     * 首先判断 body 是否存在
     * 获取body中的 mode 参数
     * 根据 mode 的值来获取 contentType
     */
    protected function obtainContentType2ApiVars(array $apiVars): string
    {
        $defaultContentType = DefaultContentType;
        if (!isset($apiVars['body'])) {
            return $defaultContentType;
        }
        if (!isset($apiVars['body']['mode'])) {
            return $defaultContentType;
        }
        $requestBody = $apiVars['body'];
        if (array_key_exists($requestBody['mode'], ContentTypeMap)) {
            return ContentTypeMap[$requestBody['mode']];
        }
        // 处理 contentType 为 Raw
        if ($requestBody['mode'] == 'raw') {
            // 当 mode == raw 时 判断option是否存在 如果存在取option的值
            $defaultContentType = DefaultRawContentType;
            if (isset($requestBody['options']) && isset($requestBody['options']['raw'])
                && isset($requestBody['options']['raw']['language'])) {
                return ContentTypeMap[$requestBody['options']['raw']['language']];
            }
        }

        return $defaultContentType;
    }

    /**
     * 获取具有KV类型的请求参数
     * @param array $apiVars
     * @return array
     */
    protected function obtainKVRequestParameters(array $apiVars): array
    {
        // 考虑到多数的请求参数为KV
        // 故默认的请求参数
        $requestDefaultParameters = [];
        if (!isset($apiVars['body'])) {
            return $requestDefaultParameters;
        }
        if (!isset($apiVars['body']['mode'])) {
            return $requestDefaultParameters;
        }

        $requestBody = $apiVars['body'];
        if ($requestBody['mode'] == 'urlencoded' || $requestBody['mode'] == 'formdata') {
            $requestDefaultParameters = $this->dealingSortProblemOfKV($requestBody[$requestBody['mode']]);
        }
        // 判断如果 mode=raw  并且 raw 有值
        if (($requestBody['mode'] == 'raw') && $requestBody['raw'] != "") {
            // 判断是否是json
            $rawDatum = json_decode($requestBody['raw'], true);
            if (json_last_error() == JSON_ERROR_NONE) {
                // 证明是json数据
                $requestDefaultParameters = $this->organizeDatum2Kv($this->recursionArr($rawDatum, null));
            }
        }

        return $requestDefaultParameters;
    }

    /**
     * 处理KV的排序问题
     * @param array $KVArr
     * @return array
     */
    protected function dealingSortProblemOfKV(array $KVArr): array
    {
        $sortKVArr = [];
        foreach ($KVArr as $KVValue) {
            $sortKV['key'] = $KVValue['key'];
            // 处理上传文件的问题
            if ($KVValue['type'] == 'file') {
                $sortKV['value'] = $sortKV['src'] ?? '';
            } else {
                $sortKV['value'] = $KVValue['value'];
            }

            $sortKV['type'] = $KVValue['type'];
            $sortKV['description'] = $KVValue['description'] ?? "暂无描述";
            $sortKVArr[] = $sortKV;
        }
        return $sortKVArr;
    }

    /**
     * 获取参数中的raw
     * @param array $apiVars
     * @return string
     */
    protected function obtainRawRequestParameters(array $apiVars): string
    {
        $requestDefaultRaw = "";
        if (!isset($apiVars['body'])) {
            return $requestDefaultRaw;
        }
        if (!isset($apiVars['body']['raw'])) {
            return $requestDefaultRaw;
        }
        return $apiVars['body']['raw'];
    }

}