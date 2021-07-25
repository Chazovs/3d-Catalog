<?php


namespace Chazov\Unimarket\Model;

/**
 * Class CategoryModel
 * @package Chazov\Unimarket\Model
 */
class CategoryModel
{
    /** @var string $name */
    public $name;

    /** @var int $id */
    public $id;

    /** @var int $parentId */
    public $parentId;

    /** @var string $picture */
    public $picture;

    /** @var int $depthLevel */
    public $depthLevel;

    /** @var int $parentSection */
    public $parentSection;

    /** @var ItemModel[] */
    public $items = [];
}
