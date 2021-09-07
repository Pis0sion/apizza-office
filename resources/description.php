<?php

// 空描述
const EmptyDescriptions = "暂无描述";

// postman 成功相应码
const SuccessPostManCode = 200;

// postman 成功默认的空数据
const SuccessPostManRaw = "{}";

// project 模块默认列表
const ProjectDefaultList = "默认列表";

// post默认的接口描述
const ApiDefaultDescription = "接口描述";

// 空url
const ApiDefaultEmptyUrl = "http://{{api_url}}";

// 默认的ContentType
const DefaultContentType = "application/json";

// 默认Raw的ContentType
const DefaultRawContentType = "text/plain";

// content-type
const ContentTypeMap = [
    'formdata' => 'multipart/form-data',
    'urlencoded' => 'application/x-www-form-urlencoded',
    'json' => 'application/json',
    'javascript' => 'application/javascript',
    'html' => 'text/html',
    'xml' => 'application/xml',
    'plain' => 'text/plain'
];

// default parameter formatter  postman
const DefaultParamFormatter = [
    '参数名称' => 2000,
    '示例值' => 1800,
    '类型' => 1200,
    '参数说明' => 3000,
];


// eolinker

// Eolinker Parameters
const EolinkerDefaultMethod = "post";

// Eolinker default request
const EolinkerDefaultContentType = "application/x-www-form-urlencoded";

// Eolinker parameters
const EolinkerParamFormatter = [
    '参数名称' => 2000,
    '类型' => 1200,
    '必填' => 1200,
    '参数说明' => 3600,
];

// eolinker param type
const EolinkerParamTypeMapping = [
    0 => 'string',
    3 => 'integer',
];

// eolinker method type
const EolinkerMethodsMapping = [
    0 => "post",
    1 => "get",
    2 => "put",
    3 => "delete",
    4 => "head",
    5 => "options",
    6 => "patch",
];

// eolinker content type
const EolinkerContentTypeMapping = [
    0 => "application/x-www-form-urlencoded",
    1 => "raw",
    2 => "application/json",
    3 => "application/xml",
];

// version format
const VersionFormatter = [
    '日期' => 1800,
    '版本' => 1500,
    '说明' => 4000,
    '作者' => 1500,
];

// versions
const ProjectVersion = [
    [
        'param' => "2021/4/8",
        'namely' => "V0.0.1",
        'isBool' => "新增文档",
        'desc' => "任哥",
    ],
    [
        'param' => "2021/4/10",
        'namely' => "V0.0.2",
        'isBool' => "格式调整及描述修改",
        'desc' => "任哥",
    ],
    [
        'param' => "2021/5/8",
        'namely' => "V1.0.0",
        'isBool' => "新增鉴权",
        'desc' => "任哥、小赵",
    ],
    [
        'param' => "2021/5/11",
        'namely' => "V1.0.1",
        'isBool' => "修改鉴权",
        'desc' => "小赵、宣哥",
    ],
    [
        'param' => "",
        'namely' => "",
        'isBool' => "",
        'desc' => "",
    ],
    [
        'param' => "",
        'namely' => "",
        'isBool' => "",
        'desc' => "",
    ],
    [
        'param' => "",
        'namely' => "",
        'isBool' => "",
        'desc' => "",
    ],
    [
        'param' => "",
        'namely' => "",
        'isBool' => "",
        'desc' => "",
    ],
];


if (!function_exists("halt")) {
    function halt(array $parameters)
    {
        file_put_contents("./out.json", json_encode($parameters, JSON_UNESCAPED_UNICODE));
        die();
    }
}