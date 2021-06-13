<?php

namespace Chazov\Unimarket\Model\Response;

use Chazov\Unimarket\Model\CatalogModel;
use Chazov\Unimarket\Model\CategoryModel;

class CatalogResponse extends AbstractResponse
{
    /** @var CatalogModel[] $catalogs  */
    public $catalogs;
}
