<?php


namespace Pis0sion\Docx\servlet;

use PhpOffice\PhpWord\Element\Section;

/**
 * Class SectionServlet
 * @package Pis0sion\Docx\servlet
 */
class SectionServlet
{
    /**
     * @var Section
     */
    protected $section;

    /**
     * SectionServlet constructor.
     * @param Section $section
     */
    public function __construct(Section $section)
    {
        $this->section = $section;
    }


}