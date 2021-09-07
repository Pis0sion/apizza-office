<?php


namespace Pis0sion\Docx\servlet;

use PhpOffice\PhpWord\Element\Section;
use Pis0sion\Docx\component\TablesGenerator;

/**
 * Class TableServlet
 * @package Pis0sion\Docx\servlet
 */
class TableServlet
{
    /**
     * @var Section
     */
    protected $section;

    /**
     * CommonTableEntity constructor.
     * @param Section $section
     */
    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    /**
     * @param string $bgColor
     * @return TablesGenerator
     */
    public function generation(string $bgColor): TablesGenerator
    {
        return (new TablesGenerator($this->section))->setFirstCellStyle([
            'valign' => 'center',
            'bgColor' => $bgColor,
        ])
            ->setHeaderFStyle([
                'size' => '11',
                'bold' => true
            ])->setHeaderPStyle([
                'align' => 'center',
                'lineHeight' => 1,
            ])->setCellStyle([
                'valign' => 'center'
            ])
            ->setFStyle([
                'size' => '10.5'
            ])->setPStyle([
                'align' => 'center',
                'lineHeight' => 1,
            ])->setExactHeight(false);
    }

    /**
     * 生成普通表格
     * @param array $obstruction
     * @param array $render
     * @param string $bgColor
     */
    public function run(array $obstruction, array $render, string $bgColor = 'D8D8D8')
    {
        return $this->generation($bgColor)->generateTable($obstruction, $render);
    }

    /**
     * 生成空表格
     * @param array $obstruction
     * @param string $renderDatum
     * @param string $bgColor
     */
    public function runEmptyForm(array $obstruction, string $renderDatum, string $bgColor = 'D8D8D8')
    {
        return $this->generation($bgColor)->generateUnresponsiveTable($obstruction, $renderDatum);
    }


}