<?php


namespace Pis0sion\Docx\layer;

/**
 * Interface IFactoryInterface
 * @package Pis0sion\Docx\layer
 */
interface IFactoryInterface
{
    /**
     * @param string $convertor
     * @return IParserInterface
     */
    public function createConvertor(string $convertor): IParserInterface;
}