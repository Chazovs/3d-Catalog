<?php

namespace Chazov\Unimarket\Component\Builder;

use Bitrix\Catalog\EO_CatalogIblock;
use Bitrix\Catalog\EO_CatalogIblock_Collection;
use Bitrix\Iblock\Iblock;
use CFile;
use Chazov\Unimarket\Component\Repository\CatalogRepository;
use Chazov\Unimarket\Model\CatalogModel;
use Chazov\Unimarket\Model\CategoryModel;
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

    /** @var CatalogRepository */
    private $catalogRepository;

    /**
     * CatalogResponseBuilder constructor.
     * @param CatalogRepository $catalogRepository
     */
    public function __construct(CatalogRepository $catalogRepository)
    {
        $this->catalogRepository = $catalogRepository;
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
        foreach ($this->catalogs as $catalog) {
            $model = new CatalogModel();
            $iblock = $catalog->getIblock();
            $model->name = $iblock->getName();
            $model->iblockId = $catalog->getIblockId();
            $model->code =  $iblock->getCode();
            $model->imagePath = $this->catalogRepository->getImagePath($iblock->getPicture());
            $model->categories = $this->catalogRepository->getCategoriesByIblockId($iblock->getId());
            $model->items = $this->catalogRepository->getItemsByIblockId($iblock->getId());

            $this->response->catalogs[$model->iblockId] = $model;
        }

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
