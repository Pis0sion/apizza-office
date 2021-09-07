<?php


namespace Pis0sion\Docx\entity;

use Inhere\Console\Exception\ConsoleException;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Table;
use Pis0sion\Docx\categories\eolinker\ApisEolinkerParser;
use Pis0sion\Docx\layer\AbsBaseEntity;
use Pis0sion\Docx\servlet\TableServlet;

/**
 * Class ApisEntity
 * @package Pis0sion\Docx\entity
 */
class ApisEntity extends AbsBaseEntity
{
    /**
     * @var int
     */
    public $priority = 4;

    /**
     * 生成api接口
     * @param array|null $params
     * @return mixed|void
     */
    public function run(?array $params)
    {
        $this->addCategoriesTitle("接口列表", 1);

        $apis = $params['apis'] ?? [];
        foreach ($apis as $apiModule) {
            $this->addCategoriesTitle($apiModule['module_name'], 2);
            foreach ($apiModule['module_list'] as $apiList) {
                $this->addCategoriesTitle($apiList['name'], 3, function ($section) use ($apiList) {
                    /// 渲染类型会出现文档的类型
                    /// 兼容ApiPost的格式
                    $rType = $apiList['target_type'] ?? "";
                    // 提取类型 TODO
                    if ($rType == "doc") {
                        $this->renderRawPrettyJson($section, $apiList['request']['description'] ?? '');
                        $section->addTextBreak();
                        $section->addTextBreak();
                        return;
                    }
                    /** @var Section $section */
                    $this->renderText($section, "接口地址：", $apiList['request']['api_url']);
                    $this->renderText($section, "请求类型：", $apiList['request']['contentType']);
                    $this->renderText($section, "请求方式：", strtolower($apiList['request']['method']));
                    $this->renderText($section, "接口备注：", $apiList['request']['description']);
                    $this->renderText($section, "调试工具：", $apiList['debug'] ?? "postman");
                    $section->addTextBreak();
                    // 调试工具
                    $toolType = $apiList['debug'] ?? "postman";
                    // 请求头参数说明
                    if ($apiList['request']['headers'] ?? "") {
                        $this->renderText($section, "请求头参数说明：");
                        $this->accordingType2PresentsDiffTablesBgColors($section, $apiList['request']['headers'], $toolType, 'D8D8D8');
                        $section->addTextBreak();
                    }
                    // 路径参数说明
                    if ($apiList['request']['restful'] ?? "") {
                        $this->renderText($section, "路径参数说明：");
                        $this->accordingType2PresentsDiffTablesBgColors($section, $apiList['request']['restful'], $toolType, 'D8D8D8');
                        $section->addTextBreak();
                    }
                    // URL参数说明
                    if ($apiList['request']['queries'] ?? "") {
                        $this->renderText($section, "URI参数说明：");
                        $this->accordingType2PresentsDiffTablesBgColors($section, $apiList['request']['queries'], $toolType, 'D8D8D8');
                        $section->addTextBreak();
                    }

                    $this->renderText($section, "请求体参数说明：");
                    // 根据不同的类型呈现出不同的表格
                    $requestParameters = $apiList['request']['parameters'] ?? [];
                    // 工具
                    $this->accordingType2PresentsDifferentTables($section, $requestParameters, $toolType);
                    $section->addTextBreak();
                    // 前执行脚本
                    $vPreScript = $apiList['request']['pre_script'] ?? false;
                    if ($vPreScript) {
                        $this->renderText($section, "预执行脚本：");
                        $this->renderRawPrettyJson($section, $vPreScript);
                        $section->addTextBreak();
                    }
                    // 后执行脚本
                    $vTextScript = $apiList['request']['test'] ?? false;
                    if ($vTextScript) {
                        $this->renderText($section, "后执行脚本：");
                        $this->renderRawPrettyJson($section, $vTextScript);
                        $section->addTextBreak();
                    }

                    if (!empty($apiList['request']['raws'])) {
                        $this->renderText($section, "请求示例：");
                        $this->renderRawPrettyJson($section, $apiList['request']['raws'], '4b5661', '#2eff5e');
                        $section->addTextBreak();
                    }
                    $responseBody = $apiList['response']['body'] ?? [];
                    $this->renderText($section, "返回参数说明：");
                    $this->accordingType2PresentsDifferentTables($section, $responseBody, $toolType);
                    $section->addTextBreak();
                    // 响应结果如果是文件
                    // 暂时不做处理
                    if ($apiList['response']['raw'] ?? '') {
                        $this->renderText($section, "返回示例：");
                        $this->renderRawPrettyJson($section, $apiList['response']['raw'] ?? '');
                        $section->addTextBreak();
                    }
                });
            }
        }
    }

    /**
     * @param Section $section
     * @param string $apiType
     * @param string $text
     */
    protected function renderText(Section $section, string $apiType, string $text = '')
    {
        $textRun = $section->addTextRun(['indentation' => ['left' => 480]]);
        $textRun->addText($apiType);
        if (!empty($text)) {
            $textRun->addText($text, ['italic' => true]);
        }
    }

    /**
     * 渲染 Raw
     * @param Section $section
     * @param string|null $prettyDatum
     * @param string $bgColor
     * @param string $fontColor
     */
    protected function renderRawPrettyJson(Section $section, ?string $prettyDatum, string $bgColor = 'DDEDFB', string $fontColor = 'black')
    {
        $TableCell = $section->addTable([
            'layout' => Table::LAYOUT_FIXED,
            'cellMargin' => 50,
            'alignment' => 'center'
        ]);
        $TableCell->addRow(500);
        $cell = $TableCell->addCell(8000, [
            'valign' => 'center',
            'bgColor' => $bgColor,
        ]);
        //$textRun = $cell->addTextRun(['lineHeight' => 1.2]);
        $prettyString = $this->prettyStringJson($prettyDatum);
        $result = "<div  style='font-size: 11px; font-weight: bold ; color: {$fontColor};'>" . $prettyString . "</div>";
        Html::addHtml($cell, $result, false, false);
    }

    /**
     * 美化json字符串
     * @param string $prettyJson
     * @return string
     */
    protected function prettyStringJson(string $prettyJson): string
    {
        $prettyString = "";
        $token = strtok($prettyJson, "\r\n");
        while ($token != false) {
            $token = strip_tags($token);
            $prettyString .= "<p>$token</p>";
            $token = strtok("\r\n");
        }
        return $prettyString;
    }

    /**
     * 呈现出不同的表格根据类型
     * @param Section $section
     * @param array $renderTableDatum
     * @param string $tools
     * @param string $bgColor
     * @return int
     */
    protected function accordingType2PresentsDiffTablesBgColors(Section $section, array $renderTableDatum, string $tools, string $bgColor)
    {
        if (!array_key_exists($tools, $this->toolMapping)) {
            throw new ConsoleException("暂不支持该类型");
        }

        if (count($renderTableDatum) == true) {
            (new TableServlet($section))->run($this->toolMapping[$tools], $renderTableDatum, $bgColor);
        } else {
            (new TableServlet($section))->runEmptyForm($this->toolMapping[$tools], "无请求参数 KEY/VALUE 类型", $bgColor);
        }
        return 0;
    }

    /**
     * 呈现出不同的表格根据类型
     * @param Section $section
     * @param array $renderTableDatum
     * @param string $tools
     * @return int
     */
    protected function accordingType2PresentsDifferentTables(Section $section, array $renderTableDatum, string $tools)
    {
        if (!array_key_exists($tools, $this->toolMapping)) {
            throw new ConsoleException("暂不支持该类型");
        }

        if (count($renderTableDatum) == true) {
            (new TableServlet($section))->run($this->toolMapping[$tools], $renderTableDatum);
        } else {
            (new TableServlet($section))->runEmptyForm($this->toolMapping[$tools], "无请求参数 KEY/VALUE 类型");
        }
        return 0;
    }
}