<?php

namespace Chazov\Unimarket\Controller;

use Bitrix\Main\Engine\Controller;
use Chazov\Unimarket\Component\Container\NotFoundException;
use Chazov\Unimarket\Model\Response\AbstractResponse;
use Chazov\Unimarket\Model\Response\EmptyResponse;
use Chazov\Unimarket\Service\CatalogService;

class CatalogController extends Controller
{
    /**
     * @return AbstractResponse
     */
    public function getCatalogAction(): AbstractResponse
    {
        global $uniContainer;

        try {
            /** @var CatalogService $service */
            $service = $uniContainer->get(CatalogService::class);

            return $service->getCatalog();
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