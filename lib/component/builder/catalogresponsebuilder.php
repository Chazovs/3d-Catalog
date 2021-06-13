<?php

namespace Chazov\Unimarket\Component\Builder;

use Bitrix\Catalog\EO_CatalogIblock_Collection;
use Bitrix\Main\Loader;
use \Chazov\Unimarket\Model\Response\CatalogResponse;

/**
 * Class CatalogResponseBuilder
 * @package Chazov\Unimarket\Component\Builder
 */
class CatalogResponseBuilder implements BuilderInterface
{

    /**
     * @var CatalogResponse
     */
    private $response;

    /**
     * @var EO_CatalogIblock_Collection
     */
    private $catalogs;

    /**
     * CatalogResponseBuilder constructor.
     */
    public function __construct()
    {
        $this->response = new CatalogResponse();
    }

    /**
     * @return BuilderInterface
     */
    public function reset(): BuilderInterface
    {
        $this->response = new CatalogResponse();

        return $this;
    }

    /**
     * @return BuilderInterface
     */
    public function build(): BuilderInterface
    {
        return $this;
    }

    /**
     * @return CatalogResponse
     */
    public function getResult(): CatalogResponse
    {
        return $this->response;
    }

    /**
     * @param EO_CatalogIblock_Collection $catalogs
     * @return $this
     */
    public function setCatalogs(EO_CatalogIblock_Collection $catalogs): CatalogResponseBuilder
    {
        $this->catalogs = $catalogs;

        return $this;
    }
}