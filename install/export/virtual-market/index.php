<?php

/** @var $APPLICATION */

require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php');

$APPLICATION->SetTitle('Виртуальный магазин');

$APPLICATION->IncludeComponent(
    'chazov:virtual.market',
    '',
    []
);

require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/footer.php');
