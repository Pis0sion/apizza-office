<?php


namespace Pis0sion\Docx\entity;

use Pis0sion\Docx\layer\AbsBaseEntity;
use Pis0sion\Docx\servlet\TableServlet;

/**
 * Class AppendixEntity
 * @package Pis0sion\Docx\entity
 */
class AppendixEntity extends AbsBaseEntity
{
    /**
     * @var int
     */
    public $priority = 7;

    /**
     * 处理附录
     * @param array|null $params
     * @return mixed|void
     */
    public function run(?array $params)
    {
        $this->addCategoriesTitle("附录", 1);
        $this->addCategoriesTitle("返回码列表", 2, function ($section) {
            $appendix = [
                [
                    'param' => "SYS_API_0000",
                    'namely' => "成功！",
                ],
                [
                    'param' => "SYS_API_999",
                    'namely' => "注册参数校验失败/抱歉,白名单参数有误！/ 抱歉,冻结天数超出范围！/ 传入的参数有误！",
                ],
                [
                    'param' => "SYS_API_998",
                    'namely' => "调用接口失败。",
                ],
                [
                    'param' => "SYS_API_997",
                    'namely' => "附件大小不符合规范。",
                ],
                [
                    'param' => "",
                    'namely' => "",
                ],
                [
                    'param' => "",
                    'namely' => "",
                ],
                [
                    'param' => "",
                    'namely' => "",
                ],
                [
                    'param' => "",
                    'namely' => "",
                ],
            ];
            (new TableServlet($section))->run([
                '返回码' => 2000,
                '说明' => 7000,
            ], $appendix);
            $section->addTextBreak(1);
        });
    }
}