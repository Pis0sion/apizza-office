<?php


namespace Pis0sion\Docx\layer;

/**
 * Interface IParserInterface
 * @package Pis0sion\Docx\layer
 */
interface IParserInterface
{
    /**
     * @param array $postmanArr
     * @return array
     */
    public function parse2RenderDocx(array $postmanArr): array;
}