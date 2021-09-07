<?php

[
    "apis" => [
        [
            "module_name" => "用户模块",
            "module_list" => [
                [
                    "name" => '注销接口',
                    "request" => [
                        'url' => "{{domain}}/{{version}}/logout.action",
                        'contentType' => "json",
                        'method' => "HTTP/GET",
                        'description' => "描述",
                        'header' => [
                            [
                                'key' => 'token',
                                'value' => 'dfsfdsfdsaf',
                                'type' => "text"
                            ],
                            [
                                'key' => 'token',
                                'value' => 'dfsfdsfdsaf',
                                'type' => "text"
                            ],
                            [
                                'key' => 'token',
                                'value' => 'dfsfdsfdsaf',
                                'type' => "text"
                            ]
                        ],
                        'parameters' => [
                            [
                                'key' => 'token',
                                'value' => 'dfsfdsfdsaf',
                                'type' => "text"
                            ],
                            [
                                'key' => 'token',
                                'value' => 'dfsfdsfdsaf',
                                'type' => "text"
                            ]
                        ],
                        'raws' => '',
                    ],
                    "response" => [
                        'body' => [],
                        'raw' => '{
    "errorCode": "SVCAPI_FNT_0000000",
    "errorMessage": "Success",
    "responseResult": {
        "result": "退出成功"
    }
}',
                    ],
                ],
            ],
        ],
        [
            "module_name" => "订单模块",
            "module_list" => [
                [
                    "name" => '注销接口',
                    "request" => [
                        'url' => "{{domain}}/{{version}}/logout.action",
                        'method' => "GET",
                        'contentType' => "json",
                        'desc' => "描述",
                        'header' => [
                            [
                                'key' => 'token',
                                'value' => 'dfsfdsfdsaf',
                                'type' => "text"
                            ],
                            [
                                'key' => 'token',
                                'value' => 'dfsfdsfdsaf',
                                'type' => "text"
                            ],
                            [
                                'key' => 'token',
                                'value' => 'dfsfdsfdsaf',
                                'type' => "text"
                            ]
                        ],
                        'body' => [
                            [
                                'key' => 'token',
                                'value' => 'dfsfdsfdsaf',
                                'type' => "text"
                            ],
                            [
                                'key' => 'token',
                                'value' => 'dfsfdsfdsaf',
                                'type' => "text"
                            ]
                        ],
                    ],
                    "response" => [
                        'body' => [],
                        'raw' => '{
    "errorCode": "SVCAPI_FNT_0000000",
    "errorMessage": "Success",
    "responseResult": {
        "result": "退出成功"
    }
}',
                    ],
                ],
            ],
        ],
    ],
];