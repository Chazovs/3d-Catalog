<?php


namespace Chazov\Unimarket\Component\Repository;

use Bitrix\Catalog\CatalogIblockTable;
use Bitrix\Catalog\EO_CatalogIblock_Collection;
use Bitrix\Catalog\EO_Product;
use Bitrix\Catalog\PriceTable;
use Bitrix\Catalog\ProductTable;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\EO_Element;
use Bitrix\Iblock\EO_Section;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use CFile;
use Chazov\Unimarket\Component\Logger\LoggerInterface;
use Chazov\Unimarket\Component\Logger\LogLevel;
use Chazov\Unimarket\Model\CategoryModel;
use Chazov\Unimarket\Model\ItemModel;

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
     */
    public function __construct(LoggerInterface $logger)
    {
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

    /**
     * @param int $getPicture
     * @return string|null
     */
    public function getImagePath(int $getPicture): ?string
    {
        return CFile::GetPath($getPicture);
    }

    /**
     * @param int $iblockId
     *
     * @return CategoryModel[]
     */
    public function getCategoriesByIblockId(int $iblockId): array
    {
        $categoryModels = [];

        try {
            $sections = SectionTable::query()
                ->setSelect(['*'])
                ->where('IBLOCK_ID', $iblockId)
                ->fetchCollection();

            /** @var EO_Section $section */
            foreach ($sections as $section) {
                $model = new CategoryModel();
                $model->name = $section->getName();
                $model->id = $section->getId();
                $model->picture = $this->getImagePath($section->getPicture());
                $model->depthLevel = $section->getDepthLevel();
                $model->parentSection = $section->getParentSection();

                $categoryModels[$section->getId()] = $model;
            }
        } catch (ObjectPropertyException | ArgumentException | SystemException $exception) {
            $this->logger->log(LogLevel::ERROR, $exception->getMessage());
        }

        return $categoryModels;
    }

    /**
     * @param int $iblockId
     * @return ItemModel[]
     */
    public function getItemsByIblockId(int $iblockId): array
    {
        $itemModels = [];

        try {
            $items = ProductTable::query()
                ->setSelect(['*', 'ELEMENT', 'PRICE'])
                ->registerRuntimeField(
                    'ELEMENT',
                    [
                        "data_type" => ElementTable::class,
                        'reference' => ['=this.ID' => 'ref.ID'],
                        'join_type' => "LEFT"
                    ]
                )
                ->registerRuntimeField(
                    'PRICE',
                    [
                        "data_type" => PriceTable::class,
                        'reference' => ['=this.ID' => 'ref.PRODUCT_ID'],
                        'join_type' => "LEFT"
                    ]
                )
                ->where('IBLOCK_ELEMENT.IBLOCK_ID', $iblockId)
                ->fetchCollection();

            /** @var EO_Product $item */
            foreach ($items as $item) {
                $model = new ItemModel();

                /** @var EO_Element $element */
                $element = $item->get('ELEMENT');
                $priceTable = $item->get('PRICE');

                $model->name = $element->getName();
                $model->description
                    = !empty($element->getPreviewText())
                    ? $element->getPreviewText()
                    : $element->getDetailText();
                $model->itemId = $item->getId();
                $model->imagePath = $this->getImagePath(
                    $element->getDetailPicture()
                    ?? $element->getPreviewPicture()
                );
                $model->categoryId = $element->getIblockSectionId();
                $model->price = $priceTable->getPrice();

                $itemModels[] = $model;
            }
        } catch (ObjectPropertyException | ArgumentException | SystemException $exception) {
            $this->logger->log(LogLevel::ERROR, $exception->getMessage());
        }

        return $itemModels;
    }
}
