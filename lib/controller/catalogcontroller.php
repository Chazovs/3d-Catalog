<?php

namespace Chazov\Unimarket\Controller;

use Bitrix\Catalog\CatalogIblockTable;
use Bitrix\Catalog\EO_CatalogIblock_Collection;
use Bitrix\Catalog\EO_Product;
use Bitrix\Iblock\Iblock;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;
use Bitrix\Sale\ProductTable;
use Chazov\Unimarket\Component\Repository\CatalogRepository;

class Catalog extends Controller
{
    /**
     * @return string
     */
    public function getCatalogAction(): string
    {
        $repository = new CatalogRepository();

        /** @var EO_CatalogIblock_Collection $catalogs */
        $catalogs = $repository->getCatalogs();

        $catalogs = CatalogIblockTable::query()
            ->setSelect(['IBLOCK_ID', 'IBLOCK'])
            ->where('PRODUCT_IBLOCK_ID', 0)
            ->exec()
            ->fetchCollection();


      foreach ($catalogs as $catalog){
         /** @var Iblock $iblockProducts */
          $iblockProducts = $catalog
              ->getIblock();


          /** @var  $product */
          foreach ($iblockProducts as $product) {
$product->getId();
          }
      }

      /* $products = ProductTable::query()->exec()->fetchCollection();

        foreach ($products as $product) {
$product->getId();
$x = $product->getId();
$y = 2;
        }*/
        return '';
    }
}

/*BX.ajax.runAction('chazov:unimarket.api.catalog.getcatalog',
                {
                    method: 'POST',
                    data:   {
    sessid: BX.bitrix_sessid(),
                    }
                }
            ).then((response) => {
    console.log(response.data)

});*/