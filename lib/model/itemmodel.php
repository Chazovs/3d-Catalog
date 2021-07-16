<?php


namespace Chazov\Unimarket\Model;

/**
 * Class Item
 * @package Chazov\Unimarket\Model
 */
class ItemModel
{
    /** @var string */
    public $name;

    /** @var string */
    public $imagePath;

    /** @var string */
    public $model3dPath;

    /** @var string */
    public $description;

    /** @var int $itemId */
    public $itemId;

    /** @var int $categoryId */
    public $categoryId;

    /** @var float $price */
    public $price;
}
