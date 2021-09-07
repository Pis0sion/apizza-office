<?php


namespace Pis0sion\Docx\servlet;

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Class CoverServlet
 * @package Pis0sion\Docx\servlet
 */
class CoverServlet
{
    /**
     * @var Section
     */
    protected $section;

    /**
     * CoverServlet constructor.
     * @param Section $section
     */
    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    /**
     * 制作封面
     */
    public function createCoverNoImages()
    {
        $header = $this->section->addHeader();
        $header->firstPage();

        $projectBox = $this->section->addTextBox(
            array(
                'alignment' => Jc::CENTER,
                'width' => 400,
                'height' => 350,
                'borderColor' => '#FFFFFF',
                'valign' => 'center',
            )
        );
        $projectBox->addTextBreak(8);
        $projectBox->addText('项目接口文档', ['size' => 38, 'color' => 'black'], ['align' => 'center']);

        $this->section->addTextBreak();

        $authorBox = $this->section->addTextBox(
            array(
                'alignment' => Jc::CENTER,
                'width' => 400,
                'height' => 250,
                'borderColor' => '#FFFFFF',
                'valign' => 'center',
            )
        );

        $authorBox->addTextBreak(8);
        $authorBox->addText("某某网络有限公司", ['size' => 12, 'color' => '4F81BD'], ['align' => 'right']);
        $authorBox->addText("2020/12/30", ['size' => 12, 'color' => '4F81BD'], ['align' => 'right']);
    }

    /**
     * 制作封面
     * @param string $chapterName
     * @param string $author
     * @param int $create_time
     */
    public function createCover(string $chapterName, string $author, int $create_time)
    {
        $header = $this->section->addHeader();
        $header->firstPage();

        $this->section->addImage(
            './resources/cover.png',
            [
                'width' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(14),
                'height' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(21),
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'wrappingStyle' => "behind",
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_CENTER,
                'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
                'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
            ]
        );
        $this->section->addTextBreak(3);
        $this->section->addText($chapterName, ['size' => 38, 'color' => 'white'], ['align' => 'center']);
//        $projectBox->addTextBreak(8);
//        $projectBox->addText('项目接口文档', ['size' => 38, 'color' => 'black'], ['align' => 'center']);
//
//        $this->section->addTextBreak();
//
        $this->section->addTextBreak(20);
        $authorBox = $this->section->addTextBox(
            array(
                'alignment' => Jc::CENTER,
                'width' => 460,
                'height' => 100,
                'borderColor' => '#FFFFFF',
                'valign' => 'center',
            )
        );
        $authorBox->addTextBreak(2);
        $authorBox->addText("{$author} 制作", ['size' => 14, 'color' => '185ABD'], ['align' => 'right']);
        $authorBox->addText(date("Y-m-d", $create_time), ['size' => 14, 'color' => '185ABD'], ['align' => 'right']);
    }
}