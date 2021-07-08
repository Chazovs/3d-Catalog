<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Chazov\Unimarket\Component\Container\NotFoundException;
use Chazov\Unimarket\Service\CatalogService;

try {
    if (!Loader::includeModule('chazov.unimarket')) {
        die();
    }

    global $uniContainer;

    /** @var CatalogService $catalogService */
    $catalogService = $uniContainer->get(CatalogService::class);
    $arResult['CATALOG'] = $catalogService->getCatalog();
} catch (NotFoundException | LoaderException $exception) {
    AddMessage2Log($exception->getMessage());
}
