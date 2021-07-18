<?php

namespace Chazov\Unimarket\Controller;

use Bitrix\Catalog\Product\Basket;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\NotImplementedException;
use Bitrix\Sale\BasketBase;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Fuser;
use \Bitrix\Sale\Basket as SaleBasket;
use Chazov\Unimarket\Component\Container\NotFoundException;
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
     * @return \array[][]
     */
    public function configureActions(): array
    {
        return [
            'getBasket' => [
                '-prefilters' => [
                    Authentication::class,
                    Csrf::class
                ],
            ],
            'addToBasket' => [
                '-prefilters' => [
                    Authentication::class,
                    Csrf::class
                ],
            ],
            'deleteFromBasket' => [
                '-prefilters' => [
                    Authentication::class,
                    Csrf::class
                ],
            ],
        ];
    }

    /**
     * @param int $itemId
     * @param int $quantity
     * @return BasketResponse
     */
    public function addToBasketAction(int $itemId, int $quantity = 1): BasketResponse
    {
        $basket = $this->changeItemInBasket($itemId, $quantity);

        return $this->getBasketResponse($basket ?? null);
    }

    /**
     * @param int $itemId
     * @param int $quantity
     * @return BasketResponse
     */
    public function deleteFromBasketAction(int $itemId, int $quantity = -1): BasketResponse
    {
        $basket = $this->changeItemInBasket($itemId, $quantity);

        return $this->getBasketResponse($basket ?? null);
    }

    /**
     * @return BasketResponse
     * @throws ArgumentException
     * @throws ArgumentTypeException
     * @throws NotImplementedException
     */
    public function getBasketAction(): BasketResponse
    {
        $basket = SaleBasket::loadItemsForFUser(
            Fuser::getId(),
            Context::getCurrent()->getSite()
        );

        return $this->getBasketResponse($basket ?? null);
    }

    /**
     * @param BasketBase|null $basket
     * @return BasketResponse
     */
    private function getBasketResponse(?BasketBase $basket): BasketResponse
    {
        global $uniContainer;

        $basketResponse = new BasketResponse();

        if (null === $basket) {
            return $basketResponse;
        }

        try {
            /** @var Logger $logger */
            $logger = $uniContainer->get(Logger::class);
        } catch (NotFoundException $exception) {
            AddMessage2Log($exception->getMessage());
        }

        try {
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
                $itemModel->categoryId = 0;//Записывать первую из категорий товара

                $basketResponse->basketItems[] = $itemModel;
            }

        } catch (Throwable $exception) {
            $logger->log(LogLevel::ERROR, $exception->getMessage());
        }

        return $basketResponse;
    }

    /**
     * @param int $itemId
     * @param int $quantity
     * @return BasketBase|null
     */
    private function changeItemInBasket(int $itemId,  int $quantity = 1): ?BasketBase
    {
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

            return $basket;
        } catch (Throwable $exception) {
            $logger->log(LogLevel::ERROR, $exception->getMessage());
        }

        return null;
    }
}
