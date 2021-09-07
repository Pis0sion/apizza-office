#!/usr/bin/env php
<?php

use Inhere\Console\Application;
use Inhere\Console\BuiltIn\PharController;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use Pis0sion\Docx\command\GenDocxCommand;

require "vendor/autoload.php";

$meta = [
    'name' => 'Convert Json To Docx App',
    'version' => '1.0.8',
];

$app = new Application($meta, new Input(), new Output());

$errHandler = new \Pis0sion\Docx\exception\ConsoleException();

// 设置异常处理
$app->setErrorHandler($errHandler);

$app->command('fixed', function (Input $input, Output $output) {
    $cmd = $input->getCommand();
    $output->info("v1.0.8 fixed wps json format : " . $cmd);
});

$app->command(GenDocxCommand::class);
$app->controller(PharController::class);

$app->run();

