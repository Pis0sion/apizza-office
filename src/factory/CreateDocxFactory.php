<?php


namespace Pis0sion\Docx\factory;

use Inhere\Console\Exception\ConsoleException;
use JsonPath\InvalidJsonException;
use JsonPath\JsonObject;
use Pis0sion\Docx\Core;
use Pis0sion\Docx\servlet\CoverServlet;
use Pis0sion\Docx\servlet\FooterServlet;
use Pis0sion\Docx\servlet\HeaderServlet;
use Pis0sion\Docx\servlet\PhpWordServlet;
use Pis0sion\Docx\servlet\TableServlet;
use Pis0sion\Docx\servlet\TocServlet;
use Throwable;

/**
 * Class CreateDocxFactory
 * @package Pis0sion\Docx\factory
 */
class CreateDocxFactory
{
    /**
     * @param string $inputJson
     * @param string $outDocx
     * @param string $apiTools
     * @return array
     * @throws InvalidJsonException
     */
    public function run(string $inputJson, string $outDocx, string $apiTools): array
    {
        $errCode = '1000';
        $errMessage = '转换成功';
        $resultResponse = ['file' => $outDocx];

        // 获取json数据
        $convertJson = file_get_contents($inputJson);
        $jsonArr = json_decode($convertJson, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new ConsoleException(json_last_error_msg());
        }

        // 初始化示例
        $phpWordServlet = new PhpWordServlet();
        // 初始化
        $isProject = false;
        $_document_password = "";
        if ($apiTools == "apipost") {
            // 获取加密密码
            $_document_password = $jsonArr['password'];
            if (trim($_document_password) != "") {
                $isProject = true;
            }
        }
        if ($isProject == true) {
            // 添加保护密码
            $phpWordServlet->init(true, $_document_password);
        } else {
            // 不保护
            $phpWordServlet->init();
        }

        // 创建封面
        $cover = $phpWordServlet->newSection();
        (new CoverServlet($cover))->createCover($jsonArr['pub_name'], $jsonArr['publisher'], (int)$jsonArr['create_time']);
        // 创建页面
        // 设置页面边框大小颜色
        $section = $phpWordServlet->newSection(['borderColor' => '161616', 'borderSize' => 6]);
        // 创建页眉页脚
        (new HeaderServlet($section->addHeader()))->setHeader();
        (new FooterServlet($section->addFooter()))->setFooter();

        // 版本内容
        (new TableServlet($section))->run(VersionFormatter, ProjectVersion);
        $section->addPageBreak();
        // 创建目录
        (new TocServlet($section))->setTOC();

        // 失败抛异常
        try {
            $apis = (new ConvertorFactory())->createConvertor($apiTools)->parse2RenderDocx($jsonArr);
        } catch (Throwable $throwable) {
            throw new ConsoleException($throwable->getMessage());
        }

        $apis['apis'] = $apis;
        if ($apiTools == "apipost") {
            $apis['folders'] = $this->obtainsChapters2ApipostJson($convertJson);
        }

        // 生成文档
        (new Core())->run($section, $apis);
        // 保存文件
        $phpWordServlet->saveAs($outDocx);

        return compact('errCode', 'errMessage', 'resultResponse');
    }

    /**
     * @param array $inputArr
     * @param string $outDocx
     * @param string $apiTools
     * @return array
     * @throws InvalidJsonException
     */
    public function runAndDownLoad(array $inputArr, string $outDocx, string $apiTools): array
    {
        // 初始化示例
        $phpWordServlet = new PhpWordServlet();
        // 初始化
        $isProject = false;
        $_document_password = "";
        if ($apiTools == "apipost") {
            // 获取加密密码
            $_document_password = $inputArr['password'];
            if (trim($_document_password) != "") {
                $isProject = true;
            }
        }
        if ($isProject == true) {
            // 添加保护密码
            $phpWordServlet->init(true, $_document_password);
        } else {
            // 不保护
            $phpWordServlet->init();
        }

        // 创建封面
        $cover = $phpWordServlet->newSection();
        (new CoverServlet($cover))->createCover($inputArr['pub_name'], $inputArr['publisher'], (int)$inputArr['create_time']);
        // 创建页面
        // 设置页面边框大小颜色
        $section = $phpWordServlet->newSection(['borderColor' => '161616', 'borderSize' => 6]);
        // 创建页眉页脚
        (new HeaderServlet($section->addHeader()))->setHeader();
        (new FooterServlet($section->addFooter()))->setFooter();

        // 版本内容
        (new TableServlet($section))->run(VersionFormatter, ProjectVersion);
        $section->addPageBreak();
        // 创建目录
        (new TocServlet($section))->setTOC();

        // 失败抛异常
        try {
            $apis = (new ConvertorFactory())->createConvertor($apiTools)->parse2RenderDocx($inputArr);
        } catch (Throwable $throwable) {
            throw new ConsoleException($throwable->getMessage());
        }

        $apis['apis'] = $apis;
        if ($apiTools == "apipost") {
            $apis['folders'] = $this->obtainsChapters2ApipostJson(json_encode($inputArr, JSON_UNESCAPED_UNICODE));
        }

        // 生成文档
        (new Core())->run($section, $apis);
        // 保存文件
        $phpWordServlet->saveAs($outDocx);

        if (!file_exists($outDocx)) {
            header('HTTP/1.1 404 NOT FOUND');
        } else {
            $file = fopen($outDocx, "rb");
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . filesize($outDocx));
            Header("Content-Disposition: attachment; filename=" . basename($outDocx));
            echo fread($file, filesize($outDocx));
            fclose($file);
        }
        exit();
    }

    /**
     * 获取所有的目录
     * @param string $apipostJson
     * @return array
     * @throws InvalidJsonException
     */
    protected function obtainsChapters2ApipostJson(string $apipostJson): array
    {
        $rValue = [];
        $jsonObject = new JsonObject($apipostJson);
        if ($jsonResult = $jsonObject->get("$..children[?(@.target_type == 'folder')]")) {
            $rValue = $jsonResult;
        }
        return $rValue;
    }

}