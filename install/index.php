<?php

use Bitrix\Catalog\EO_CatalogIblock;
use Bitrix\Catalog\EO_CatalogIblock_Collection;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Chazov\Unimarket\Component\ConfigProvider;
use Chazov\Unimarket\Component\Constants;
use Chazov\Unimarket\Component\Container\Container;
use Chazov\Unimarket\Component\Container\LockServiceException;
use Chazov\Unimarket\Component\Container\NotFoundException;
use Chazov\Unimarket\Component\Logger\Logger;
use Chazov\Unimarket\Component\Repository\CatalogRepository;

IncludeModuleLangFile(__FILE__);

/**
 * Class chazov_unimarket
 */
class chazov_unimarket extends CModule
{
    public $MODULE_ID = 'chazov.unimarket';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    public $errors;
    /**
     * @var Container
     */
    private $uniContainer;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * chazov_unimarket constructor.
     */
    public function __construct()
    {
        $this->MODULE_VERSION = '0.1';
        $this->MODULE_VERSION_DATE = '08.08.2021';
        $this->MODULE_NAME = GetMessage('CHAZOV_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('CHAZOV_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = GetMessage('MODULE_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('MODULE_PARTNER_URI');

        Loader::includeModule('catalog');

        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/service/catalogservice.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/model/response/abstractresponse.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/model/response/catalogresponse.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/component/builder/builderinterface.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/component/builder/catalogresponsebuilder.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/component/repository/catalogrepository.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/component/logger/loggerinterface.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/component/logger/logger.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/component/configprovider.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/component/constants.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/component/container/containerinterface.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/lib/component/container/container.php');

        $this->uniContainer = new Container();

        try {
            $this->uniContainer->configContainer();
        } catch (LockServiceException $exception) {
            echo 'Обнаружен зацикливающий вызов';
        } catch (NotFoundException | ReflectionException $exception) {
            echo $exception->getMessage();
        }

        $this->logger = $this->uniContainer->get(Logger::class);
    }

    /**
     * @return bool
     */
    public function DoInstall(): bool
    {
        $this->copyFiles();
        $this->createModelField();
        RegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     * @return bool
     */
    public function DoUninstall(): bool
    {
        $this->deleteFiles();

        UnRegisterModule($this->MODULE_ID);

        return true;
    }

    private function copyFiles(): void
    {
        CopyDirFiles(
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . $this->MODULE_ID . '/install/export',
            $_SERVER['DOCUMENT_ROOT'],
            true,
            true,
            false
        );
    }

    private function deleteFiles(): void
    {
        DeleteDirFiles(
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . $this->MODULE_ID . '/install/export',
            $_SERVER['DOCUMENT_ROOT']
        );
    }

    private function createModelField(): void
    {
        try {
            $propsIds = [];

            /** @var EO_CatalogIblock $catalog */
            foreach ($this->getSimpleCatalogNumvers() as $catalog) {
                $catalogId = $catalog->get('IBLOCK_ID');

                $property = PropertyTable::query()
                    ->setSelect(['ID'])
                    ->where('CODE', Constants::UNIMARKET_MODEL)
                    ->where('IBLOCK_ID', $catalogId)
                    ->fetchObject();

                if (null !== $property && null !== $property->getId()) {
                    $propsIds[Constants::MODULE_PREFIX . $catalogId] =$property->getId();
                    continue;
                }

                $prop = [
                    "IBLOCK_ID"     => $catalogId,
                    "CODE"          => Constants::UNIMARKET_MODEL,
                    "PROPERTY_TYPE" => 'F',
                    "ACTIVE"        => 'Y',
                    "NAME"          => 'Зд модель',
                ];

                $iblockProperty = new CIBlockProperty;

                if (!$propId = $iblockProperty->Add($prop)) {
                    /** @var Logger $logger */
                    $this->logger->log('ERROR', 'Не удалось создать свойство для хранения модели');
                }

                $propsIds[Constants::MODULE_PREFIX . $catalogId] = $propId;
            }

            ConfigProvider::setUniFields($propsIds);
        } catch (NotFoundException | ArgumentException | SystemException $exception) {
            $this->logger->log('ERROR', $exception->getMessage());
        }
    }

    /**
     * @return EO_CatalogIblock_Collection|null
     * @throws NotFoundException
     */
    private function getSimpleCatalogNumvers(): ?EO_CatalogIblock_Collection
    {
        /** @var Container $uniContainer */
        $repository = $this->uniContainer->get(CatalogRepository::class);

        return $repository->getCatalogs();
    }
}