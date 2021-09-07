<?php


namespace Pis0sion\Docx\entity;

use PhpOffice\PhpWord\Element\Section;
use Pis0sion\Docx\layer\AbsBaseEntity;

/**
 * Class ExportEntity
 * @package Pis0sion\Docx\entity
 */
class ExportEntity extends AbsBaseEntity
{
    /**
     * @var int
     */
    public $priority = 6;

    /**
     * 处理导入
     * @param array|null $params
     * @return mixed|void
     */
    public function run(?array $params)
    {
        $this->addCategoriesTitle("接入导入", 1);
        $this->addCategoriesTitle("如何快速导入", 2, function ($section) {
            /** @var Section $section */
            $section->addListItem('获取 DEMO 压缩包：', 0, null, "access");
            $section->addListItem('将请求地址改为测试环境请求地址；', 0, null, 'access');
            $section->addListItem('目前仅有 PHP 范例；', 0, null, 'access');
            $section->addListItem('将范例部署到您的应用服务器，并运行；', 0, null, 'access');
            $section->addListItem('在测试环境上请调通接口；', 0, null, 'access');
            $section->addListItem('请求和响应都调通后，便可在范例中加入您系统本身的业务逻辑，并再次在测试环境进行调试，直至通过；', 0, null, 'access');
            $section->addListItem('将您正式的密钥配置到程序中，并将请求地址改为正式环境请求地址后便可上线。', 0, null, 'access');
            $section->addTextBreak(1);
        });
        $this->addCategoriesTitle("参数转码无效的解决方案", 2, function ($section) {
            /** @var Section $section */
            $section->addText("解决方法：", null, ['indentation' => ['firstLine' => 480]]);
            $section->addText("首先检查配置中的参数是否与系统上提供的参数一致,是否有中文。", null, ['indentation' => ['firstLine' => 480]]);
            $section->addText("其次处理中文转码问题,有两个需要正确转码的环节：", null, ['indentation' => ['firstLine' => 480]]);
            $section->addListItem('涉及中文的参数在传入生成url的方法时，注意编码问题。', 0, null, 'resolve');
            $section->addListItem('生成请求参数后，涉及中文的参数的值不能是乱码，当前仅支持UTF-8的编码格式。', 0, null, 'resolve');
            $section->addPageBreak();
        });
    }
}