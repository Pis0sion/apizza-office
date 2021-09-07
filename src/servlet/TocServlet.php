<?php


namespace Pis0sion\Docx\servlet;

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Style\TOC;

/**
 * Class TocServlet
 * @package Pis0sion\Docx\servlet
 */
class TocServlet
{
    /**
     * @var Section
     */
    protected $section;

    /**
     * @var array
     */
    protected $fontStyle = ['spacing' => 8, 'lineHeight' => 1.5, 'size' => 12];

    /**
     * @var array
     */
    protected $tocStyle = ['tabLeader' => TOC::TAB_LEADER_DOT, 'indent' => 100];

    /**
     * TocServlet constructor.
     * @param Section $section
     */
    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    /**
     * 设置目录
     */
    public function setTOC()
    {
        $this->section->addTitle('目录', 0);           // 添加主题，不写入目录中 depth:0
        $this->section->addTOC($this->getFontStyle(), $this->getTocStyle());
        $this->section->addPageBreak();
    }

    /**
     * @return array
     */
    public function getFontStyle(): array
    {
        return $this->fontStyle;
    }

    /**
     * @param array $fontStyle
     */
    public function setFontStyle(array $fontStyle): void
    {
        $this->fontStyle = $fontStyle;
    }

    /**
     * @return array
     */
    public function getTocStyle(): array
    {
        return $this->tocStyle;
    }

    /**
     * @param array $tocStyle
     */
    public function setTocStyle(array $tocStyle): void
    {
        $this->tocStyle = $tocStyle;
    }

}