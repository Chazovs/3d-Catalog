<?php

namespace Chazov\Unimarket\Model\Response;

use Chazov\Unimarket\Model\CatalogModel;
use Chazov\Unimarket\Model\CategoryModel;
use Chazov\Unimarket\Model\ItemModel;

class BasketResponse extends AbstractResponse
{
    /** @var BasketItemModel[] $basketItems  */
    public $basketItems;

    /** @var float $totalPrice */
    public $totalPrice;
}
