<?php


namespace Pis0sion\Docx\servlet;

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\DocProtect;
use Pis0sion\Docx\component\PropertiesGenerator;

/**
 * Class PhpWordServlet
 * @package Pis0sion\Docx\servlet
 */
class PhpWordServlet
{
    /**
     * @var PhpWord
     */
    protected $phpWord;

    /**
     * PhpWordServlet constructor.
     */
    public function __construct()
    {
        $this->initialize();
        $this->phpWord = new PhpWord();
    }

    /**
     * initialize global settings
     */
    protected function initialize()
    {
        // XML Writer兼容性 默认为 true  开发建议 false
        Settings::setCompatibility(false);
        // 输出转义
        Settings::setOutputEscapingEnabled(true);
    }

    /**
     * 初始化phpWord
     * @param bool $isProtected
     * @param string $password
     */
    public function init(bool $isProtected = false, $password = "123456")
    {
        $this->setAccessPermissionsForDocuments($isProtected, $password);
        $this->setPropertiesOfDocument();
        $this->setGlobalSettingsOfDocument();
        $this->setStyleOfVariousHeadings();
        $this->registerReadObjectStyle();
        $this->registerSignatureStyle();
        $this->registerCallbackStyle();
        $this->registerManualStyle();
        $this->registerDescriptionStyle();
        $this->registerAccessStyle();
        $this->registerResolveStyle();
    }

    /**
     * 设置文档的访问权限
     * @param bool $isProtected
     * @param string $password
     */
    protected function setAccessPermissionsForDocuments(bool $isProtected, string $password)
    {
        $documentProtection = $this->phpWord->getSettings()->getDocumentProtection();
        if ($isProtected) {
            $documentProtection->setEditing(DocProtect::READ_ONLY);
            $documentProtection->setPassword($password);
        }
    }

    /**
     * 设置文档的全局属性
     */
    protected function setPropertiesOfDocument()
    {
        (new PropertiesGenerator($this->phpWord))->setAttributeOfDocument();
    }

    /**
     * 设置文档的全局设置
     * @param int $defaultFontSize
     * @param float $lineHeight
     * @param int $paragraphSize
     */
    protected function setGlobalSettingsOfDocument(int $defaultFontSize = 11, float $lineHeight = 1.5, int $paragraphSize = 12)
    {
        // 强制更新文档中的动态的字面量
        $this->phpWord->getSettings()->setUpdateFields(true);
        // 拼写和语法检查
        $this->phpWord->getSettings()->setHideGrammaticalErrors(true);
        $this->phpWord->getSettings()->setHideSpellingErrors(true);
        // 设置全局的字体大小
        $this->phpWord->setDefaultFontName("Calibri");
        $this->phpWord->setDefaultFontSize($defaultFontSize);
        // 设置全局的行高还有段落字体
        $this->phpWord->setDefaultParagraphStyle(['lineHeight' => $lineHeight, 'size' => $paragraphSize]);
    }

    /**
     * 设置各类标题的样式
     */
    protected function setStyleOfVariousHeadings()
    {
        // 默认设置四级标题
        // 每类标题的字体大小
        $this->setNumberingStyle('hNum',
            [
                array('pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1'),
                array('pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2'),
                array('pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3'),
            ]
        );

        // 设置四级标题
        // 设置一级标题
        $this->phpWord->addTitleStyle(null, array('size' => 20, 'bold' => true, 'spacing' => 8, 'lineHeight' => 1.8));
        // 设置二级标题
        $this->phpWord->addTitleStyle(1, array('size' => 18, 'color' => '333333', 'bold' => true, 'lineHeight' => 1), array('numStyle' => 'hNum', 'numLevel' => 0));
        // 设置三级标题
        $this->phpWord->addTitleStyle(2, array('size' => 14, 'color' => '333333', 'bold' => true, 'lineHeight' => 1), array('numStyle' => 'hNum', 'numLevel' => 1));
        // 设置四级标题
        $this->phpWord->addTitleStyle(3, array('size' => 12, 'color' => '333333', 'lineHeight' => 1), array('numStyle' => 'hNum', 'numLevel' => 2));
    }

    /**
     * 默认注册readObject样式
     */
    protected function registerReadObjectStyle()
    {
        $this->setNumberingStyle(
            'readObject',
            [
                ['format' => 'decimal', 'text' => '%1.', 'left' => 1080, 'hanging' => 360, 'tabPos' => 720]
            ]
        );
    }

    /**
     * 默认注册signature样式
     */
    protected function registerSignatureStyle()
    {
        $this->setNumberingStyle(
            'signature',
            [
                ['format' => 'decimal', 'text' => '%1.', 'left' => 1080, 'hanging' => 360, 'tabPos' => 720],
                ['format' => 'decimalEnclosedCircle', 'text' => '%2.', 'left' => 1440, 'hanging' => 360, 'tabPos' => 720],
            ]
        );
    }

    /**
     * 默认注册callback样式
     */
    protected function registerCallbackStyle()
    {
        $this->setNumberingStyle(
            'callback',
            [
                ['format' => 'decimal', 'text' => '%1.', 'left' => 1080, 'hanging' => 360, 'tabPos' => 720],
            ]
        );
    }

    /**
     * 默认注册manual样式
     */
    protected function registerManualStyle()
    {
        $this->setNumberingStyle(
            'manual',
            [
                ['format' => 'decimal', 'text' => '%1.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],
                ['format' => 'upperLetter', 'text' => '%2.', 'left' => 1080, 'hanging' => 360, 'tabPos' => 720],
            ]
        );
    }

    /**
     * 默认注册description样式
     */
    protected function registerDescriptionStyle()
    {
        $this->setNumberingStyle(
            'description',
            [
                ['format' => 'decimal', 'text' => '%1.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],
            ]
        );
    }

    /**
     * 默认注册access样式
     */
    protected function registerAccessStyle()
    {
        $this->setNumberingStyle(
            'access',
            [
                ['format' => 'decimalEnclosedCircle', 'text' => '%1.', 'left' => 900, 'hanging' => 360, 'tabPos' => 720],
            ]
        );
    }

    /**
     * 默认注册resolve样式
     */
    protected function registerResolveStyle()
    {
        $this->setNumberingStyle(
            'resolve',
            [
                ['format' => 'decimalEnclosedCircle', 'text' => '%1.', 'left' => 900, 'hanging' => 360, 'tabPos' => 720],
            ]
        );
    }

    /**
     * 设置列表编号
     * @param string $attributeName
     * @param array $levels
     */
    public function setNumberingStyle(string $attributeName, array $levels)
    {
        $this->phpWord->addNumberingStyle(
            $attributeName,
            [
                'type' => 'multilevel',
                'levels' => $levels,
            ]
        );
    }

    /**
     * 创建 section
     * @param array $style
     * @return Section
     */
    public function newSection(array $style = []): Section
    {
        return $this->phpWord->addSection($style);
    }

    /**
     * 保存文档
     * @param string $filePath
     * @param bool $download
     */
    public function saveAs(string $filePath, bool $download = false)
    {
        $this->phpWord->save($filePath, $format = 'Word2007', $download = false);
    }

}