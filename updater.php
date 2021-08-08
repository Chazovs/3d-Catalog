<?php
//for version 0.1.0
use Chazov\Unimarket\Component\Constants;

CopyDirFiles(
    $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . Constants::MODULE_ID . '/install/export',
    $_SERVER['DOCUMENT_ROOT'],
    true,
    true,
    false
);
