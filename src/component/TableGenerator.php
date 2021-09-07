<?php


namespace Pis0sion\Docx\component;

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;

/**
 * 表格生成器
 * Class TableGenerator
 * @package Pis0sion\Docx\component
 */
class TableGenerator
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
     */
    public function setTableStyle(array $tableStyle): void
    {
        $this->tableStyle = $tableStyle;
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
     */
    public function setFirstRowStyle(array $firstRowStyle): void
    {
        $this->firstRowStyle = $firstRowStyle;
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
     */
    public function setRowStyle(array $rowStyle): void
    {
        $this->rowStyle = $rowStyle;
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
     */
    public function setCellStyle(array $cellStyle): void
    {
        $this->cellStyle = $cellStyle;
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
     */
    public function setHeaderFStyle(array $headerFStyle): void
    {
        $this->headerFStyle = $headerFStyle;
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
     */
    public function setHeaderPStyle(array $headerPStyle): void
    {
        $this->headerPStyle = $headerPStyle;
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
     */
    public function setFStyle(array $fStyle): void
    {
        $this->fStyle = $fStyle;
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
     */
    public function setPStyle(array $pStyle): void
    {
        $this->pStyle = $pStyle;
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
     */
    public function setFirstCellStyle(array $firstCellStyle): void
    {
        $this->firstCellStyle = $firstCellStyle;
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
     */
    public function setExactHeight(bool $exactHeight): void
    {
        $this->exactHeight = $exactHeight;
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


}