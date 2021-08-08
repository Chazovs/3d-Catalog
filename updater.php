<?php

CopyDirFiles(
    $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/chazov.unimarket/install/export',
    $_SERVER['DOCUMENT_ROOT'],
    true,
    true,
    false
);
