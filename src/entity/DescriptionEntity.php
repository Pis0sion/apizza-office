<?php


namespace Pis0sion\Docx\entity;

use Inhere\Console\Exception\ConsoleException;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Table;
use Pis0sion\Docx\layer\AbsBaseEntity;
use Pis0sion\Docx\servlet\TableServlet;

/**
 * Class DescriptionEntity
 * @package Pis0sion\Docx\entity
 */
class DescriptionEntity extends AbsBaseEntity
{
    /**
     * @var int
     */
    public $priority = 3;

    /**
     * 创建目录说明
     * @param array|null $params
     */
    public function run(?array $params)
    {
        if (!array_key_exists("folders", $params)) {
            $this->addCategoriesTitle("接口说明", 1, function ($section) {
                /** @var Section $section */
                $section->addListItem('在未特别注明的情况下，所有的接口均可采用HTTP的POST提交方式发起请求，采用Application/json提交方式提交数据。', 0, null, 'description');
                $section->addListItem('所有接口全部采用HTTPS请求方式。', 0, null, 'description');
                $section->addListItem('数据返回格式为JSON串。', 0, null, 'description');
                $section->addTextBreak(1);
                $section->addTextBreak(1);
            });
            return;
        }

        $this->addCategoriesTitle("全局参数列表", 1);
        $folders = $params['folders'] ?? [];
        foreach ($folders as $folder) {
            $this->addCategoriesTitle($folder['name'], 2, function ($section) use ($folder) {
                $toolType = "ApiPost";
                /** @var Section $section */
                $this->renderText($section, "目录描述：", $this->chapter2ApiPostDescription($folder));
                $section->addTextBreak();
                // 渲染目录的全局参数
                // 请求头参数说明
                $vHeaders = $this->obtainKVRequestParameters($folder['request']['header']);
                if ($vHeaders) {
                    $this->renderText($section, "请求头参数说明：");
                    $this->accordingType2PresentsDiffTablesBgColors($section, $vHeaders, $toolType, 'D8D8D8');
                    $section->addTextBreak();
                }
                // URL参数说明
                $vQueries = $this->obtainKVRequestParameters($folder['request']['query']);
                if ($vQueries) {
                    $this->renderText($section, "URL参数说明：");
                    $this->accordingType2PresentsDiffTablesBgColors($section, $vQueries, $toolType, 'D8D8D8');
                    $section->addTextBreak();
                }
                // 请求参数说明
                $vBodies = $this->obtainKVRequestParameters($folder['request']['body']);
                if ($vBodies) {
                    $this->renderText($section, "请求参数说明：");
                    $this->accordingType2PresentsDiffTablesBgColors($section, $vBodies, $toolType, 'D8D8D8');
                    $section->addTextBreak();
                }
                // 前执行脚本
                $vPreScript = $folder['script']['pre_script'] ?? false;
                if ($vPreScript) {
                    $this->renderText($section, "前执行脚本：");
                    $this->renderRawPrettyJson($section, $vPreScript);
                    $section->addTextBreak();
                }
                // 后执行脚本
                $vTextScript = $folder['script']['test'] ?? false;
                if ($vTextScript) {
                    $this->renderText($section, "后执行脚本：");
                    $this->renderRawPrettyJson($section, $vTextScript);
                    $section->addTextBreak();
                }
            });
        }
    }

    /**
     * 渲染文本
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
     * 获取目录描述
     * @param array $folder
     * @return string
     */
    protected function chapter2ApiPostDescription(array $folder): string
    {
        $description = "暂无描述";
        if ($folder['request']['description'] ?? "") {
            $description = $folder['request']['description'];
        }
        return $description;
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
     * 处理成kv类型
     * @param array $apiRequestParameters
     * @return array
     */
    protected function obtainKVRequestParameters(array $apiRequestParameters)
    {
        $kvResult = [];
        foreach ($apiRequestParameters as $apiRequestParameter) {
            // 排除掉键为空的字段
            if (trim($apiRequestParameter['key']) == false) {
                continue;
            }
            // 正常的数据
            $kvResult[] = [
                'key' => $apiRequestParameter['key'],
                'value' => $apiRequestParameter['value'],
                'bool' => $apiRequestParameter['is_checked'] == 1 ? '必填' : '选填',
                'description' => trim($apiRequestParameter['description']) != '' ? trim($apiRequestParameter['description']) : "暂无描述",
            ];
        }
        return $kvResult;
    }

    /**
     * 提取 raw 数据
     * @param array $apiVars
     * @param string|null $fields
     * @return string
     */
    protected function obtainRawRequestParameters(array $apiVars, string $fields = null): string
    {
        if ($fields == null) {
            return $apiVars['body']['raw'] ?? "";
        }
        return $apiVars[$fields]['raw'] ?? "";
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
        $result = "<div style='font-size: 13px;color: {$fontColor};'>" . $prettyString . "</div>";
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
}