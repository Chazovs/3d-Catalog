<?php

/** @var $APPLICATION */

require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php');

$APPLICATION->SetTitle('Виртуальный магазин');
?>


<?php
$APPLICATION->IncludeComponent(
    'chazov:virtual.market',
    '',
    []
);
?>

<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/footer.php');
