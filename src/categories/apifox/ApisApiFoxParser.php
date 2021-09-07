<?php


namespace Pis0sion\Docx\categories\apifox;


use JsonPath\InvalidJsonException;
use JsonPath\JsonObject;
use Pis0sion\Docx\layer\IParserInterface;

/**
 * Class ApisApiFoxParser
 * @package Pis0sion\Docx\categories\apifox
 */
class ApisApiFoxParser implements IParserInterface
{
    /**
     * @param array $apifoxArr
     * @return array
     * @throws InvalidJsonException
     */
    public function parse2RenderDocx(array $apifoxArr): array
    {
        return $this->organizeProjectVars2Specifications($this->arrange2ClassifyApis($apifoxArr));
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
                $moduleApi['debug'] = "Apifox";
                $moduleApi['request'] = [
                    'api_url' => $moduleApi['api']['path'],
                    'method' => $moduleApi['api']['method'],
                    'contentType' => $moduleApi['api']['requestBody']['type'],
                    'description' => count($moduleApi['api']['tags']) == 0 ? ApiDefaultDescription : implode(",", $moduleApi['api']['tags']),
                    'parameters' => $this->obtainSuccessRequestParameters($moduleApi),
                    'raws' => $moduleApi['api']['requestBody']['sampleValue'] ?? "",
                ];
                $moduleApi['response'] = [
                    'body' => [],
                    'raw' => "{}",
                ];
            }
        }
        return $projectVars;
    }

    /**
     * 整理apifox中的数据
     * @param array $apifoxArr
     * @return array
     * @throws InvalidJsonException
     */
    protected function arrange2ClassifyApis(array $apifoxArr): array
    {
        $projectVars = [];
        // 首先获取所有的分类信息
        foreach ($apifoxArr['apiCollection'] as $apifoxModule) {
            // 如果存在则为分类
            if (array_key_exists("items", $apifoxModule)) {
                // 分类数据
                $apifoxModuleListObject = new JsonObject($apifoxModule);
                // 分类
                $projectVars[] = [
                    // 分组名称
                    'module_name' => $apifoxModule['name'],
                    'module_list' => $apifoxModuleListObject->get('$..items[?(not @.items and @.name and @.api)]'),
                ];
                continue;
            }

            $module_list[] = $apifoxModule;
            // 存在子类
            $projectVars[] = [
                'module_name' => ProjectDefaultList,
                // 获取该模块的所有接口列表
                'module_list' => $module_list,
            ];
        }

        return $projectVars;
    }

    /**
     * @param array $apiDatum
     * @return array
     */
    protected function obtainSuccessRequestParameters(array $apiDatum): array
    {
        // 默认为空
        $requestKvParameters = [];
        $requestParameters = $apiDatum['api']['requestBody']['parameters'] ?? [];
        foreach ($requestParameters as $requestParameter) {
            $requestKvParameters[] = [
                'key' => $requestParameter['name'],
                'type' => $requestParameter['type'],
                'bool' => $requestParameter['required'] ? '必填' : '选填',
                'description' => $requestParameter['description'],
            ];
        }
        return $requestKvParameters;
    }
}