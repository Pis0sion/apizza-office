<?php


namespace Pis0sion\Docx\component;

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;

/**
 * 表格生成器
 * Class TableGenerator
 * @package Pis0sion\Docx\component
 */
class TablesGenerator
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var null|array
     */
    protected $tableStyle = [];

    /**
     * @var array
     */
    protected $firstRowStyle = [];

    /**
     * @var array
     */
    protected $rowStyle = [];

    /**
     * @var array
     */
    protected $firstCellStyle = [];

    /**
     * @var array
     */
    protected $cellStyle = [];

    /**
     * @var array
     */
    protected $headerFStyle = [];

    /**
     * @var array
     */
    protected $headerPStyle = [];

    /**
     * @var array
     */
    protected $fStyle = [];

    /**
     * @var array
     */
    protected $pStyle = [];

    /**
     * @var bool
     */
    protected $exactHeight = true;

    /**
     * TableGenerator constructor.
     * @param Section $section
     * @param array|null $tableStyle
     */
    public function __construct(Section $section, ?array $tableStyle = null)
    {
        $this->tableStyle = $tableStyle ?? [
                'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED,
                'borderColor' => '212529',
                'borderSize' => 8,
                'cellMargin' => 50,
                'alignment' => 'center'
            ];
        $this->table = $section->addTable($this->getTableStyle());
    }

    /**
     * @return array
     */
    public function getTableStyle(): array
    {
        return $this->tableStyle;
    }

    /**
     * @param array $tableStyle
     * @return TablesGenerator
     */
    public function setTableStyle(array $tableStyle): TablesGenerator
    {
        $this->tableStyle = $tableStyle;
        return $this;
    }

    /**
     * @return array
     */
    public function getFirstRowStyle(): array
    {
        return $this->firstRowStyle;
    }

    /**
     * @param array $firstRowStyle
     * @return TablesGenerator
     */
    public function setFirstRowStyle(array $firstRowStyle): TablesGenerator
    {
        $this->firstRowStyle = $firstRowStyle;
        return $this;
    }

    /**
     * @return array
     */
    public function getRowStyle(): array
    {
        return $this->rowStyle;
    }

    /**
     * @param array $rowStyle
     * @return TablesGenerator
     */
    public function setRowStyle(array $rowStyle): TablesGenerator
    {
        $this->rowStyle = $rowStyle;
        return $this;
    }

    /**
     * @return array
     */
    public function getCellStyle(): array
    {
        return $this->cellStyle;
    }

    /**
     * @param array $cellStyle
     * @return TablesGenerator
     */
    public function setCellStyle(array $cellStyle): TablesGenerator
    {
        $this->cellStyle = $cellStyle;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaderFStyle(): array
    {
        return $this->headerFStyle;
    }

    /**
     * @param array $headerFStyle
     * @return TablesGenerator
     */
    public function setHeaderFStyle(array $headerFStyle): TablesGenerator
    {
        $this->headerFStyle = $headerFStyle;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaderPStyle(): array
    {
        return $this->headerPStyle;
    }

    /**
     * @param array $headerPStyle
     * @return TablesGenerator
     */
    public function setHeaderPStyle(array $headerPStyle): TablesGenerator
    {
        $this->headerPStyle = $headerPStyle;
        return $this;
    }

    /**
     * @return array
     */
    public function getFStyle(): array
    {
        return $this->fStyle;
    }

    /**
     * @param array $fStyle
     * @return TablesGenerator
     */
    public function setFStyle(array $fStyle): TablesGenerator
    {
        $this->fStyle = $fStyle;
        return $this;
    }

    /**
     * @return array
     */
    public function getPStyle(): array
    {
        return $this->pStyle;
    }

    /**
     * @param array $pStyle
     * @return TablesGenerator
     */
    public function setPStyle(array $pStyle): TablesGenerator
    {
        $this->pStyle = $pStyle;
        return $this;
    }

    /**
     * @return array
     */
    public function getFirstCellStyle(): array
    {
        return $this->firstCellStyle;
    }

    /**
     * @param array $firstCellStyle
     * @return TablesGenerator
     */
    public function setFirstCellStyle(array $firstCellStyle): TablesGenerator
    {
        $this->firstCellStyle = $firstCellStyle;
        return $this;
    }

    /**
     * @return bool
     */
    public function isExactHeight(): bool
    {
        return $this->exactHeight;
    }

    /**
     * @param bool $exactHeight
     * @return TablesGenerator
     */
    public function setExactHeight(bool $exactHeight): TablesGenerator
    {
        $this->exactHeight = $exactHeight;
        return $this;
    }

    /**
     * 生成表格
     * @param array $tableHeaders
     * @param array $renderDatum
     */
    public function generateTable(array $tableHeaders, array $renderDatum)
    {
        // 首行
        $this->table->addRow(350, $this->firstRowStyle ?? null);

        // 渲染表头
        foreach ($tableHeaders as $tableContent => $header) {
            $this->table->addCell(intval($header) ?? 2000, $this->getFirstCellStyle() ?? null)
                ->addText($tableContent, $this->getHeaderFStyle(), $this->getHeaderPStyle());
        }

        // 渲染表格
        foreach ($renderDatum as $datum) {
            $this->table->addRow(350, ['exactHeight' => $this->isExactHeight()]);
            foreach ($datum as $data) {
                $this->table->addCell(null, $this->getCellStyle())->addText($data, $this->getFStyle(), $this->getPStyle());
            }
        }
    }

    /**
     * 生成合并单元格的处理无响应数据
     * @param array $tableHeaders
     * @param string $renderDatum
     */
    public function generateUnresponsiveTable(array $tableHeaders, string $renderDatum)
    {
        // 首行
        $this->table->addRow(350, $this->firstRowStyle ?? null);

        // 渲染表头
        foreach ($tableHeaders as $tableContent => $header) {
            $this->table->addCell(intval($header) ?? 2000, $this->getFirstCellStyle() ?? null)
                ->addText($tableContent, $this->getHeaderFStyle(), $this->getHeaderPStyle());
        }

        // 合并单元格
        $cellStyle = $this->getCellStyle();
        $cellStyle['gridSpan'] = (int)count($tableHeaders);
        $this->table->addRow(350, ['exactHeight' => $this->isExactHeight()]);
        $this->table->addCell(null, $cellStyle)->addText($renderDatum, $this->getFStyle(), $this->getPStyle());
    }


}