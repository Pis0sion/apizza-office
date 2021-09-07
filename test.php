<?php

use JsonPath\InvalidJsonException;
use JsonPath\JsonObject;
use Pis0sion\Docx\categories\apipost\ApisApiPostParser;
use Pis0sion\Docx\categories\postman\ApisPostManParser;
use Pis0sion\Docx\Core;
use Pis0sion\Docx\servlet\CoverServlet;
use Pis0sion\Docx\servlet\FooterServlet;
use Pis0sion\Docx\servlet\HeaderServlet;
use Pis0sion\Docx\servlet\PhpWordServlet;
use Pis0sion\Docx\servlet\TableServlet;
use Pis0sion\Docx\servlet\TocServlet;

require "./vendor/autoload.php";

// 实例化 PhpWord 对象
$phpWordServlet = new PhpWordServlet();
// 初始化
$phpWordServlet->init(true, "123456");
// 创建封面
$cover = $phpWordServlet->newSection();
(new CoverServlet($cover))->createCover("123","12313",time());
// 创建页面
// 设置页面边框大小颜色
$section = $phpWordServlet->newSection(['borderColor' => '161616', 'borderSize' => 6]);
//$header = $section->addHeader();
//$header->firstPage();
// 创建页眉页脚
(new HeaderServlet($section->addHeader()))->setHeader();
(new FooterServlet($section->addFooter()))->setFooter();
$section->addTextBreak(2);
// 版本内容
(new TableServlet($section))->run(VersionFormatter, ProjectVersion);
$section->addPageBreak();

// 创建目录
(new TocServlet($section))->setTOC();
//  TODO: postman
$apipostJson = file_get_contents("post.json");

$apipostArr = json_decode($apipostJson, true);

$projectVars = (new ApisApiPostParser())->parse2RenderDocx($apipostArr);

/**
 * 获取所有的目录
 * @param string $apipostJson
 * @return array
 * @throws InvalidJsonException
 */
function obtainsChapters2ApipostJson(string $apipostJson): array
{
    $rValue = [];
    $jsonObject = new JsonObject($apipostJson);
    if ($jsonResult = $jsonObject->get("$..children[?(@.target_type == 'folder')]")) {
        $rValue = $jsonResult;
    }
    return $rValue;
}

// 获取json数据
//$postmanJson = file_get_contents("ceshi.json");
//
//$projectVars = (new ApisPostManParser())->parse2RenderDocx($postmanJson);

//  TODO: eolinker
//$eolinkerJson = file_get_contents("./json/bao.json");
//$projectVars = (new ApisEolinkerParser())->parse2RenderDocx($eolinkerJson);

//  TODO: apipost

//  TODO: apifox
//$apifoxJson = file_get_contents("./json/apifox.json");
//$projectVars = (new ApisApiFoxParser())->parse2RenderDocx($apifoxJson);

$apis = [
    "apis" => $projectVars,
    "folders" => obtainsChapters2ApipostJson($apipostJson),
];

// 生成文档
(new Core())->run($section, $apis);

$fileName = "./" . time() . "pis0sion" . ".docx";
// 保存文件
$phpWordServlet->saveAs($fileName);

