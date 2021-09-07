<?php


namespace Pis0sion\Docx\entity;

use PhpOffice\PhpWord\Element\Section;
use Pis0sion\Docx\layer\AbsBaseEntity;

/**
 * Class ProductionEntity
 * @package Pis0sion\Docx\entity
 */
class ProductionEntity extends AbsBaseEntity
{
    /**
     * @var int
     */
    public $priority = 5;

    /**
     * 生产环境资料
     * @param array|null $params
     * @return mixed|void
     */
    public function run(?array $params)
    {
        $this->addCategoriesTitle("生产环境资料", 1);
        $this->addCategoriesTitle("如何获取服务器地址", 2, function ($section) {
            /** @var Section $section */
            $section->addText("以阿里云为例，登录阿里云后台找到 ECS 控制台，找到公网地址，复制保存即可。", null, ['indentation' => ['firstLine' => 480]]);
            $section->addTextBreak(1);
        });
        $this->addCategoriesTitle("响应返回异常", 2, function ($section) {
            /** @var Section $section */
            $section->addText("对比Postman和ApiPost实际发出的请求报文（curl、参数、body、header）是否完全吻合。", null, ['indentation' => ['firstLine' => 480]]);
            $section->addText("（关于密码的保管：贵公司一定要保证密码仅能被少数可靠的授权人知晓，严防密码被不可信的人获取，如密码泄露需立即进行修改同时替换程序中的密码。）", ['color' => 'ff0000'], ['indentation' => ['firstLine' => 480]]);
            $section->addTextBreak(1);
            $section->addTextBreak(1);
        });
    }
}