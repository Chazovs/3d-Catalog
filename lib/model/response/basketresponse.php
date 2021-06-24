<?php

namespace Chazov\Unimarket\Model\Response;

use Chazov\Unimarket\Model\BasketItemModel;


class BasketResponse extends AbstractResponse
{
    /** @var BasketItemModel[] $basketItems  */
    public $basketItems;

    /** @var float $totalPrice */
    public $totalPrice;
}
