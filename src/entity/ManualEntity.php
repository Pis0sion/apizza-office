<?php


namespace Pis0sion\Docx\entity;

use PhpOffice\PhpWord\Element\Section;
use Pis0sion\Docx\layer\AbsBaseEntity;

/**
 * Class ManualEntity
 * @package Pis0sion\Docx\entity
 */
class ManualEntity extends AbsBaseEntity
{
    /**
     * @var int
     */
    public $priority = 2;

    /**
     * 创建使用流程
     * @param array|null $params
     */
    public function run(?array $params)
    {
        $this->addCategoriesTitle("使用流程", 1, function ($section) {
            /** @var Section $section */
            $section->addListItem('准备阶段：', 0, null, "manual");
            $section->addListItem('申请测试号等信息；', 1, null, 'manual');
            $section->addListItem('取得开发手册（本文档）等资料；', 1, null, 'manual');
            $section->addListItem('开发阶段：', 0, null, 'manual');
            $section->addListItem('根据提供的 DEMO 结合开发文档快速熟悉对接接口；', 1, null, 'manual');
            $section->addListItem('根据本系统提供的接口，在自己的系统上进行开发，实现所需要的业务功能；', 1, null, 'manual');
            $section->addListItem('对自己系统的业务功能进行全面测试；', 1, null, 'manual');
            $section->addListItem('与测试环境进行联调。', 1, null, 'manual');
            $section->addListItem('生产使用：', 0, null, 'manual');
            $section->addListItem('使用系统提供的正式资料。', 1, null, 'manual');
            $section->addTextBreak(1);
            $section->addTextBreak(1);
        });
    }
}