<?php

/** @var $APPLICATION */

require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php');

$APPLICATION->SetTitle('Модель продукта');
?>


<?php
$APPLICATION->IncludeComponent(
    'chazov:virtual.product',
    '',
    [
        'ConfirmOrderUrl' => '/personal/order/make/'
    ]
);
?>

<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/footer.php');
