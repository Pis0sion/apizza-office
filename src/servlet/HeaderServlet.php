<?php


namespace Pis0sion\Docx\servlet;

use PhpOffice\PhpWord\Element\Header;
use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Class HeaderServlet
 * @package Pis0sion\Docx\servlet
 */
class HeaderServlet
{
    /**
     * @var Header
     */
    protected $header;

    /**
     * @var string
     */
    protected $headerText = "Api Document | Pis0sion";

    /**
     * @var array
     */
    protected $headerStyle = ['size' => 8, 'color' => '4f81bd'];

    /**
     * @var array
     */
    protected $headerPStyle = ['width' => 80, 'height' => 80, 'alignment' => Jc::END];

    /**
     * HeaderServlet constructor.
     * @param Header $header
     */
    public function __construct(Header $header)
    {
        $this->header = $header;
    }

    // 设置页眉
    public function setHeader()
    {
        $this->header->addText($this->getHeaderText(), $this->getHeaderStyle(), $this->getHeaderPStyle());
    }

    /**
     * @return string
     */
    public function getHeaderText(): string
    {
        return $this->headerText;
    }

    /**
     * @param string $headerText
     */
    public function setHeaderText(string $headerText): void
    {
        $this->headerText = $headerText;
    }

    /**
     * @return array
     */
    public function getHeaderStyle(): array
    {
        return $this->headerStyle;
    }

    /**
     * @param array $headerStyle
     */
    public function setHeaderStyle(array $headerStyle): void
    {
        $this->headerStyle = $headerStyle;
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
}