<?php


namespace Pis0sion\Docx\entity;

use PhpOffice\PhpWord\Element\Section;
use Pis0sion\Docx\layer\AbsBaseEntity;
use Pis0sion\Docx\servlet\TableServlet;

/**
 * 简介
 * Class IntroductionEntity
 * @package Pis0sion\Docx\entity
 */
class IntroductionEntity extends AbsBaseEntity
{
    /**
     * @var int
     */
    public $priority = 1;

    /**
     * 创建简介
     * @param array|null $params
     */
    public function run(?array $params)
    {
        $this->addCategoriesTitle("文档简介", 1);
        $this->addCategoriesTitle("特别声明", 2, function ($section) {
            $section->addText("未得到本公司的书面许可，不得为任何目的、以任何形式或手段（包括但不限于机械的或电子的）复制或传播本文档的任何部分。对于本文档涉及的技术和产品，本公司拥有其专利（或正在申请专利）、商标、版权或其它知识产权。除非得到本公司的书面许可协议，本文档不授予这些专利、商标、版权或其它知识产权的许可。", null, ['indentation' => ['firstLine' => 480]]);
            $section->addText("本文档因产品功能示例和描述的需要，所使用的任何人名、企业名和数据都是虚构的，并仅限于本公司内部测试使用，不等于本公司有对任何第三方的承诺和宣传。", null, ['indentation' => ['firstLine' => 480]]);
            $section->addTextBreak(1);
        });
        $this->addCategoriesTitle("阅读对象", 2, function ($section) {
            /** @var Section $section */
            $section->addText("贵公司的技术部门的开发、维护及管理人员，应具备以下基本知识：", null, ['indentation' => ['firstLine' => 480]]);
            $section->addListItem("了解HTTPS/HTTP协议等内容。", 0, null, "readObject");
            $section->addListItem("了解信息安全的基本概念。", 0, null, "readObject");
            $section->addListItem("了解计算机至少一种编程语言。", 0, null, "readObject");
            $section->addTextBreak(1);
        });
        $this->addCategoriesTitle("产品说明", 2, function ($section) {
            $section->addText("本开发手册对该系统功能接口进行详细的描述，通过该指南可以对本系统有全面的了解，使技术人员尽快掌握本系统的接口，并能够在本系统上进行开发。", null, ['indentation' => ['firstLine' => 480]]);
            $section->addTextBreak(1);
        });
        $this->addCategoriesTitle("名词解释", 2, function ($section) {
            $glossary = [
                [
                    'param' => "客户端",
                    'namely' => "本文档中的客户端为ApiPost客户端。",
                ],
                [
                    'param' => "服务端",
                    'namely' => "本文档中的服务端为用户端。",
                ],
                [
                    'param' => "环境变量",
                    'namely' => "本系统中动态的KV参数。",
                ],
            ];
            (new TableServlet($section))->run([
                '名词缩写' => 2500,
                '名词定义' => 5500,
            ], $glossary);
            $section->addTextBreak(1);
        });
        $this->addCategoriesTitle("接口工具测试", 2, function ($section) {
            /** @var Section $section */
            $section->addListItem('测试工具推荐ApiPost接口测试工具。', 0, null, "signature");
            $section->addListItem('ApiPost测试工具使用方法如下：', 0, null, "signature");
            $section->addListItem('将网关地址或者接口地址正确输入url；', 1, null, "signature");
            $section->addListItem('将所有参数按照下面目录和接口定义中参数从上到下顺序填写；', 1, null, "signature");
            $section->addListItem('将目录的全局参数或者脚本正确写入；', 1, null, "signature");
            $section->addListItem('将接口的参数和自定义脚本内容填入；', 1, null, "signature");
            $section->addListItem('自定义脚本支持请求报文加密的数据拼装。', 0, null, "signature");
            $section->addTextBreak(1);
            $section->addTextBreak(1);
        });
    }
}