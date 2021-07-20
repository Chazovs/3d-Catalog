<?php

namespace Chazov\Unimarket\Component\Builder;

use Bitrix\Catalog\EO_CatalogIblock_Collection;
use Chazov\Unimarket\Component\Repository\CatalogRepository;
use Chazov\Unimarket\Model\CatalogModel;
use Chazov\Unimarket\Model\CategoryModel;
use Chazov\Unimarket\Model\ItemModel;
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
            $catalogModel = new CatalogModel();

            $iblock = $catalog->getIblock();
            $catalogModel->name = $iblock->getName();
            $catalogModel->iblockId = $catalog->getIblockId();
            $catalogModel->code = $iblock->getCode();
            $catalogModel->imagePath = $this->catalogRepository->getFilePath($iblock->getPicture());
            $catalogModel->categories = $this->catalogRepository->getCategoriesByIblockId($iblock->getId());
            $items = $this->catalogRepository->getItemsByIblockId($iblock->getId());//TODO вынести в билдер из репозитория
            $catalogModel->itemCount = count($items);

            $this->addItemsToCategory($catalogModel->categories, $items);
            $this->dropEmptyCategories($catalogModel->categories);

            if (!empty($catalogModel->categories)) {
                $this->response->catalogs[$catalogModel->iblockId] = $catalogModel;
            }
        }

        $this->response->success = true;

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

    /**
     * @param CategoryModel[]|null $categories
     * @param ItemModel[] $items
     */
    private function addItemsToCategory(?array &$categories, array $items): void
    {
        if (null === $categories){
            return;
        }

        foreach ($items as $item) {
            $categories[$item->categoryId]->items[] = $item;
        }
    }

    /**
     * @param CategoryModel[]|null $categories
     */
    private function dropEmptyCategories(?array &$categories): void
    {
        if (null === $categories) {
            return;
        }

        foreach ($categories as $key=>$category) {
            if (count($category->items) === 0) {
                unset($categories[$key]);
            }
        }
    }
}
