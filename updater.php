<?php

CopyDirFiles(
    $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/install/export/virtual-product',
    $_SERVER['DOCUMENT_ROOT'] . '/virtual-product',
    true,
    true,
    false
);
