<?php


namespace Pis0sion\Docx\layer;

use Closure;
use PhpOffice\PhpWord\Element\Section;

/**
 * Class AbsBaseEntity
 * @package Pis0sion\Docx\layer
 */
abstract class AbsBaseEntity implements IEntityInterface
{
    /**
     * @var int
     */
    public $priority;

    /**
     * @var Section
     */
    protected $section;

    /**
     * @var array
     */
    protected $toolMapping = [
        'postman' => DefaultParamFormatter,
        'Eolinker' => EolinkerParamFormatter,
        'Apifox' => EolinkerParamFormatter,
        'ApiPost' => DefaultParamFormatter,
    ];

    /**
     * IntroductionEntity constructor.
     * @param Section $section
     */
    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    /**
     * 处理逻辑
     * @param array|null $params
     * @return mixed
     */
    abstract public function run(?array $params);

    /**
     * 设置分类主题的目录
     * @param string $titleName
     * @param int $depth
     * @param Closure|null $render
     */
    protected function addCategoriesTitle(string $titleName, int $depth, Closure $render = null)
    {
        $this->section->addTitle($titleName, $depth);       // 添加主题，并且写入目录
        $this->section->addTextBreak(1);
        ($render instanceof Closure) && $render($this->section);
    }
}