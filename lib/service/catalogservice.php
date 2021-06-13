<?php

namespace Chazov\Unimarket\Service;

use Bitrix\Catalog\EO_CatalogIblock_Collection;
use Chazov\Unimarket\Component\Builder\CatalogResponseBuilder;
use Chazov\Unimarket\Component\Repository\CatalogRepository;

/**
 * Class CatalogService
 */
class CatalogService
{
    /**
     * @var CatalogResponseBuilder
     */
    private $builder;

    /**
     * @var CatalogRepository
     */
    private $repository;

    public function __construct(CatalogRepository $repository, CatalogResponseBuilder $builder)
    {
        $this->repository = $repository;
        $this->builder = $builder;
    }

    /**
     * @return mixed
     */
    public function getCatalog()
    {
        /** @var EO_CatalogIblock_Collection $catalogs */
        $catalogs = $this->repository->getCatalogs();

        return  $this->builder->setCatalogs($catalogs)->build()->getResult();
    }
}