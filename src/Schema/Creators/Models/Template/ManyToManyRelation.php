<?php


namespace Abdelrahmanrafaat\SchemaToCode\Schema\Creators\Models\Template;


use Abdelrahmanrafaat\SchemaToCode\Helpers\MigrationHelpers;
use Abdelrahmanrafaat\SchemaToCode\Helpers\ModelHelpers;
use Abdelrahmanrafaat\SchemaToCode\Helpers\StringHelpers;

class ManyToManyRelation implements RelationBuilderInterface
{
    /**
     * @var string
     */
    protected $relation;
    /**
     * @var string
     */
    protected $model;
    /**
     * @var string
     */
    protected $relatedModel;

    /**
     * ManyToManyRelation constructor.
     *
     * @param string $relation
     * @param string $model
     * @param string $relatedModel
     */
    public function __construct(string $relation, string $model, string $relatedModel)
    {
        $this->relation     = $relation;
        $this->model        = $model;
        $this->relatedModel = $relatedModel;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        $tab                    = StringHelpers::tab();
        $methodName             = ModelHelpers::modelNameToMethodName($this->relatedModel, true);
        $pivotTable             = MigrationHelpers::manyToManyTableName($this->model, $this->relatedModel);
        $modelForeignKey        = ModelHelpers::modelNameToForeignKey($this->model);
        $relatedModelForeignKey = ModelHelpers::modelNameToForeignKey($this->relatedModel);
        $newLine                = PHP_EOL;

        return "{$tab}public function {$methodName}(){$newLine}" .
               "{$tab}{{$newLine}" .
               "{$tab}{$tab}return \$this->{$this->relation}({$this->relatedModel}::class, '{$pivotTable}', '{$modelForeignKey}', '{$relatedModelForeignKey}')->withTimestamps();{$newLine}" .
               "{$tab}}{$newLine}{$newLine}";
    }
}