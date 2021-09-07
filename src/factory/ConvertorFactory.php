<?php


namespace Pis0sion\Docx\factory;

use Inhere\Console\Exception\ConsoleException;
use Pis0sion\Docx\categories\apipost\ApisApiPostParser;
use Pis0sion\Docx\categories\eolinker\ApisEolinkerParser;
use Pis0sion\Docx\categories\postman\ApisPostManParser;
use Pis0sion\Docx\layer\IFactoryInterface;
use Pis0sion\Docx\layer\IParserInterface;
use ReflectionClass;
use ReflectionException;

/**
 * Class ConvertorFactory
 * @package Pis0sion\Docx\factory
 */
class ConvertorFactory implements IFactoryInterface
{
    /**
     * @var string[]
     */
    protected $convertors = [
        'postman' => ApisPostManParser::class,
        'eolinker' => ApisEolinkerParser::class,
        'apipost' => ApisApiPostParser::class,
    ];

    /**
     * @param string $convertor
     * @return IParserInterface
     * @throws ReflectionException
     */
    public function createConvertor(string $convertor): IParserInterface
    {
        // TODO: Implement createConvertor() method.
        if (!array_key_exists($convertor, $this->convertors)) {
            throw new ConsoleException('not support api tools');
        }

        $refInstance = new ReflectionClass($this->convertors[$convertor]);
        /** @var IParserInterface $instance */
        $instance = $refInstance->newInstance();
        return $instance;
    }
}