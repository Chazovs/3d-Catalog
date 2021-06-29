<?php


namespace Chazov\Unimarket\Model;

/**
 * Class CatalogModel
 * @package Chazov\Unimarket\Model
 */
class CatalogModel
{
    /** @var string $name */
    public $name;

    /** @var int $iblockId */
    public $iblockId;

    /** @var CategoryModel[]|null $categories */
    public $categories;

    /** @var string $code */
    public $code;

    /** @var string $imagePath*/
    public $imagePath;

    /**
     * @var int
     */
    public $itemCount;
}
