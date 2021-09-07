<?php


namespace Pis0sion\Docx\command;


use Inhere\Console\Command;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use JsonPath\InvalidJsonException;
use Pis0sion\Docx\factory\CreateDocxFactory;

/**
 * Class GenDocxCommand
 * @package Pis0sion\Docx\command
 */
class GenDocxCommand extends Command
{

    /**
     * @var string
     */
    protected static $name = 'gen';

    /**
     * @var string
     */
    protected static $description = 'postman convert docx';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->createDefinition()
            ->setExample($this->parseCommentsVars('{script} {command} postman.json postman.docx --tools postman'))
            ->addArgument('inputJson', Input::ARG_REQUIRED, 'description for the argument [inputJson], is optional')
            ->addArgument('outDocx', Input::ARG_REQUIRED, 'description for the argument [outDocx], is optional')
            ->addOption('tools', null, Input::OPT_OPTIONAL, 'description for the option [tools], is required');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return int|mixed
     * @throws InvalidJsonException
     */
    protected function execute($input, $output)
    {
        // TODO: Implement execute() method.
        $inputJson = $input->getArg('inputJson');
        $outDocx = $input->getArg('outDocx');
        // 默认postman
        $apiTools = trim($input->getOpt('tools', 'postman'));
        $resultResponse = (new CreateDocxFactory())->run($inputJson, $outDocx, $apiTools);
        $output->json($resultResponse);
    }
}