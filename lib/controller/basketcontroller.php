<?php

namespace Chazov\Unimarket\Controller;

use Bitrix\Catalog\Product\Basket;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\Controller;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Fuser;
use \Bitrix\Sale\Basket as SaleBasket;
use Chazov\Unimarket\Component\Logger\Logger;
use Chazov\Unimarket\Component\Logger\LogLevel;
use Chazov\Unimarket\Model\BasketItemModel;
use Chazov\Unimarket\Model\ItemModel;
use Chazov\Unimarket\Model\Response\BasketResponse;
use Throwable;

/**
 * Class CatalogController
 * @package Chazov\Unimarket\Controller
 */
class BasketController extends Controller
{
    /**
     * @param int $itemId
     * @param int $quantity
     * @return BasketResponse
     */
    public function addToBasketAction(int $itemId, int $quantity = 1): BasketResponse
    {
        $basketResponse = new BasketResponse();

        try {
            global $uniContainer;

            /** @var Logger $logger */
            $logger = $uniContainer->get(Logger::class);

            $basket = SaleBasket::loadItemsForFUser(
                Fuser::getId(),
                Context::getCurrent()->getSite()
            );

            //TODO заменить на  Bitrix\Catalog\Product\Basket::addProduct($fields)
            if ($existItem = $basket->getExistsItem('catalog', $itemId)) {
                $existItem->setField('QUANTITY', $existItem->getQuantity() + $quantity);
            } else {
                $newItem = $basket->createItem('catalog', $itemId);
                $newItem->setFields([
                    'QUANTITY'               => $quantity,
                    'CURRENCY'               => CurrencyManager::getBaseCurrency(),
                    'LID'                    => Context::getCurrent()->getSite(),
                    'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                ]);
            }

            $basket->save();

            $basketResponse->totalPrice = $basket->getPrice();

            /** @var BasketItem $item */
            foreach ($basket->getBasketItems() as $item) {
                if (empty($item->getId())) {
                    continue;
                }

                $itemModel = new BasketItemModel();
                $itemModel->price = $item->getPrice();
                $itemModel->itemId = $item->getId();
                $itemModel->name = $item->getField("NAME");
                $itemModel->quantity = $item->getQuantity();

                $basketResponse->basketItems[$item->getId()] = $itemModel;
            }
        } catch (Throwable $exception) {
            $logger->log(LogLevel::ERROR, $exception->getMessage());
        }

        return $basketResponse;
    }

    /**
     * @return \array[][]
     */
    public function configureActions(): array
    {
        return [
            'addToBasket' => [
                '-prefilters' => [
                    //todo убрать это при деплое
                    Authentication::class,
                ],
            ],
        ];
    }
}
