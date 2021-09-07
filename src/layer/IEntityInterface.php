<?php


namespace Pis0sion\Docx\layer;

/**
 * Interface IEntityInterface
 * @package Pis0sion\Docx\layer
 */
interface IEntityInterface
{
    /**
     * @param array|null $params
     * @return mixed
     */
    public function run(?array $params);
}