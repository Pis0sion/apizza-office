<?php


namespace Pis0sion\Docx\component;

use PhpOffice\PhpWord\PhpWord;

/**
 * Class PropertiesGenerator
 * @package Pis0sion\Docx\component
 */
class PropertiesGenerator
{
    /**
     * @var PhpWord
     */
    protected $phpWord;

    /**
     * PropertiesGenerator constructor.
     * @param PhpWord $phpWord
     */
    public function __construct(PhpWord $phpWord)
    {
        $this->phpWord = $phpWord;
    }

    /**
     * 设置文档的基本属性信息
     */
    public function setAttributeOfDocument()
    {
        $properties = $this->phpWord->getDocInfo();
        $properties->setCreator('Gaoqiaoxue');
        $properties->setCompany('Gaoqiaoxue');
        $properties->setTitle('Api document');
        $properties->setDescription('My description');
        $properties->setCategory('My category');
        $properties->setLastModifiedBy('Gaoqiaoxue');
        $properties->setCreated(mktime(0, 0, 0, 12, 19, 2020));
        $properties->setModified(mktime(0, 0, 0, 12, 19, 2020));
        $properties->setSubject('My subject');
        $properties->setKeywords('my, key, word');
    }
}