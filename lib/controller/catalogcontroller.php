<?php

namespace Chazov\Unimarket\Controller;

use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Controller;
use Chazov\Unimarket\Component\Container\NotFoundException;
use Chazov\Unimarket\Model\Response\AbstractResponse;
use Chazov\Unimarket\Model\Response\EmptyResponse;
use Chazov\Unimarket\Service\CatalogService;

/**
 * Class CatalogController
 * @package Chazov\Unimarket\Controller
 */
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

    /**
     * @return \array[][]
     */
    public function configureActions(): array
    {
        return [
            'getCatalog' => [
                '-prefilters' => [
                    Authentication::class,
                    Csrf::class//TODO убрать перед релизом
                ],
            ],
        ];
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