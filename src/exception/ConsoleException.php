<?php


namespace Pis0sion\Docx\exception;


use Inhere\Console\AbstractApplication;
use Inhere\Console\Application;
use Inhere\Console\Contract\ErrorHandlerInterface;
use Inhere\Console\Exception\PromptException;
use Throwable;

/**
 * Class ConsoleException
 * @package Pis0sion\Docx\exception
 */
class ConsoleException implements ErrorHandlerInterface
{

    /**
     * @param Throwable $e
     * @param AbstractApplication $app
     */
    public function handle(Throwable $e, AbstractApplication $app): void
    {
        if ($e instanceof PromptException) {
            $app->getOutput()->error($e->getMessage());
            return;
        }

        $line = $e->getLine();
        $file = $e->getFile();
        $message = $e->getMessage();

        $errCode = '999';
        $errMessage = '转换失败';
        $resultResponse = compact('line', 'file', 'message');

        $app->getOutput()->json(compact('errCode', 'errMessage', 'resultResponse'));
    }
}