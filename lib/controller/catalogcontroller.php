<?php

namespace Chazov\Unimarket\Controller;

use Bitrix\Catalog\EO_CatalogIblock_Collection;
use Bitrix\Main\Engine\Controller;
use Chazov\Unimarket\Component\Builder\CatalogResponseBuilder;
use Chazov\Unimarket\Component\Container\NotFoundException;
use Chazov\Unimarket\Component\Repository\CatalogRepository;
use Chazov\Unimarket\Model\Response\AbstractResponse;
use Chazov\Unimarket\Model\Response\EmptyResponse;

class CatalogController extends Controller
{
    /**
     * @return AbstractResponse
     */
    public function getCatalogAction(): AbstractResponse
    {
        global $uniContainer;

        try {
            /** @var CatalogRepository $repository */
            $repository = $uniContainer->get(CatalogRepository::class);

            /** @var EO_CatalogIblock_Collection $catalogs */
            $catalogs = $repository->getCatalogs();

            /** @var CatalogResponseBuilder $responseBuilder */
            $responseBuilder = $uniContainer->get(CatalogResponseBuilder::class);

            return $responseBuilder->setCatalogs($catalogs)->build()->getResult();
        } catch (NotFoundException $exception) {
            return new EmptyResponse($exception->getMessage(), false);
        }
    }
}

/*BX.ajax.runAction('chazov:unimarket.api.catalogcontroller.getcatalog',
                {
                    method: 'POST',
                    data:   {
    sessid: BX.bitrix_sessid(),
                    }
                }
            ).then((response) => {
    console.log(response.data)

});*/