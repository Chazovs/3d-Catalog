<?php

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
     * chazov_unimarket constructor.
     */
    public function __construct()
    {
        $this->MODULE_VERSION = "0.1";
        $this->MODULE_VERSION_DATE = "09.06.2021";
        $this->MODULE_NAME = GetMessage('CHAZOV_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('CHAZOV_MODULE_DESCRIPTION');
        $this->PARTNER_NAME =GetMessage('MODULE_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('MODULE_PARTNER_URI');
    }

    /**
     * @return bool
     */
    public function DoInstall(): bool
    {
        $this->copyFiles();

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
}