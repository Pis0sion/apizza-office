<?php
/**
 * Created by PhpStorm.
 * - the is a config file for compile phar package.
 * User: Inhere
 * Date: 2018/1/26 0026
 * Time: 22:11
 * @var \Inhere\Console\Component\PharCompiler $compiler
 */

// config
$compiler
    // ->stripComments(false)
    ->collectVersion(false)
    ->setShebang(true)
    ->addExclude([
        'demo',
        'gen',
    ])
    ->addFile([
        'composer.json',
        'composer.lock',
    ])
    ->setCliIndex('console.php');

// Console 下的 Command Controller 命令类不去除注释，注释上是命令帮助信息
$compiler->setStripFilter(static function ($file) {
    /** @var \SplFileInfo $file */
    $name = $file->getFilename();

    return false === strpos($name, 'Command.php') && false === strpos($name, 'Controller.php');
});