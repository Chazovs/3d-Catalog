<?php


namespace Chazov\Unimarket\Component\Repository;

use Bitrix\Catalog\CatalogIblockTable;
use Bitrix\Catalog\EO_CatalogIblock_Collection;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Chazov\Unimarket\Component\Logger\LoggerInterface;
use Chazov\Unimarket\Component\Logger\LogLevel;

/**
 * Class CatalogRepository
 * @package Chazov\Unimarket\Component\Repository
 */
class CatalogRepository
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * CatalogRepository constructor.
     *
     * @throws LoaderException
     */
    public function __construct(LoggerInterface $logger)
    {
        Loader::includeModule('sale');
        Loader::includeModule('catalog');

        $this->logger = $logger;
    }

    /**
     * @return EO_CatalogIblock_Collection|null
     */
    public function getCatalogs(): ?EO_CatalogIblock_Collection
    {
        try {
            return CatalogIblockTable::query()
                ->setSelect(['IBLOCK_ID', 'IBLOCK'])
                ->where('PRODUCT_IBLOCK_ID', 0)
                ->exec()
                ->fetchCollection();
        } catch (SystemException $exception) {
            $this->logger->log(LogLevel::ERROR, $exception->getMessage());

            return null;
        }
    }
}