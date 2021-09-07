<?php


namespace Pis0sion\Docx;

use PhpOffice\PhpWord\Element\Section;

use Pis0sion\Docx\layer\IEntityInterface;
use SplObjectStorage;

/**
 * Class Core
 * @package Pis0sion\Docx
 */
class Core
{
    /**
     * @var array
     */
    protected $entitiesArr = [
        'src/entity/ApisEntity.php',
        'src/entity/DescriptionEntity.php',
        'src/entity/AppendixEntity.php',
        'src/entity/ExportEntity.php',
        'src/entity/IntroductionEntity.php',
        'src/entity/ManualEntity.php',
        'src/entity/ProductionEntity.php',
    ];

    /**
     * @var SplObjectStorage
     */
    protected $objectStorage;

    /**
     * Core constructor.
     */
    public function __construct()
    {
        $this->objectStorage = new \SplQueue();
    }

    /**
     * 初始化模块
     * @param Section $section
     */
    protected function initialize(Section $section)
    {
        $sortAttr = [];
        foreach ($this->entitiesArr as $entity) {
            $className = $this->getClassName($entity);
            $entityObj = new $className($section);
            $sortAttr[$entityObj->priority] = $entityObj;
        }
        ksort($sortAttr);
        foreach ($sortAttr as $value) {
            $this->appendObject2Queue($value);
        }
    }

    /**
     * 运行
     * @param Section $section
     * @param array $params
     */
    public function run(Section $section, array $params)
    {
        $this->initialize($section);
        while (!$this->objectStorage->isEmpty()) {
            $this->objectStorage->dequeue()->run($params);
        }
    }

    /**
     * @param IEntityInterface $IEntity
     */
    protected function appendObject2Queue(IEntityInterface $IEntity)
    {
        $this->objectStorage->enqueue($IEntity);
    }

    /**
     * @param string $filepath
     * @return string
     */
    protected function getClassName(string $filepath): string
    {
        return "\\Pis0sion\\Docx\\entity\\" . pathinfo($filepath, PATHINFO_FILENAME);
    }
}