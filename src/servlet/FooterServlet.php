<?php


namespace Pis0sion\Docx\servlet;

use PhpOffice\PhpWord\Element\Footer;
use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Class FooterServlet
 * @package Pis0sion\Docx\servlet
 */
class FooterServlet
{
    /**
     * @var Footer
     */
    protected $footer;

    /**
     * @var string
     */
    protected $format = "页 {PAGE}/{NUMPAGES}";

    /**
     * @var ?array
     */
    protected $fontStyle = null;

    /**
     * @var ?array
     */
    protected $PStyle = ['alignment' => Jc::CENTER];

    /**
     * @var string
     */
    protected $linkUrl = "http://www.gaoqiaoxue.com";

    /**
     * @var string
     */
    protected $linkText = "Pis0sion 制作";

    /**
     * FooterServlet constructor.
     * @param Footer $footer
     */
    public function __construct(Footer $footer)
    {
        $this->footer = $footer;
    }

    /**
     * 设置页脚
     */
    public function setFooter()
    {
        $this->footer->addPreserveText($this->getFormat(), $this->getFontStyle(), $this->getPStyle());
        $this->footer->addLink($this->getLinkUrl(), $this->getLinkText());
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    /**
     * @return array|null
     */
    public function getFontStyle(): ?array
    {
        return $this->fontStyle;
    }

    /**
     * @param array|null $fontStyle
     */
    public function setFontStyle(?array $fontStyle): void
    {
        $this->fontStyle = $fontStyle;
    }

    /**
     * @return array|null
     */
    public function getPStyle(): ?array
    {
        return $this->PStyle;
    }

    /**
     * @param array|null $PStyle
     */
    public function setPStyle(?array $PStyle): void
    {
        $this->PStyle = $PStyle;
    }

    /**
     * @return string
     */
    public function getLinkUrl(): string
    {
        return $this->linkUrl;
    }

    /**
     * @param string $linkUrl
     */
    public function setLinkUrl(string $linkUrl): void
    {
        $this->linkUrl = $linkUrl;
    }

    /**
     * @return string
     */
    public function getLinkText(): string
    {
        return $this->linkText;
    }

    /**
     * @param string $linkText
     */
    public function setLinkText(string $linkText): void
    {
        $this->linkText = $linkText;
    }


}