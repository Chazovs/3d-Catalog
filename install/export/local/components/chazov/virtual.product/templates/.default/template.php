<?php
/** @var $arResult */
CUtil::InitJSCore(['ajax', 'jquery', 'popup']); ?>

<link rel="stylesheet" href="/virtual-product/TemplateData/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<button id="openModel"><?= GetMessage('OPEN_MODEL_BUTTON') ?></button>
<div id="unity-container" class="unity-desktop">
    <span class="popup-window-close-icon popup-window-titlebar-close-icon" onclick="$('#product_3d').hide()"
          style="right: 20px; top: 10px;"></span>
    <canvas id="unity-canvas"></canvas>
    <div id="unity-loading-bar">
        <div id="unity-logo"></div>
        <div id="unity-progress-bar-empty">
            <div id="unity-progress-bar-full"></div>
        </div>
    </div>
    <div id="unity-footer">
        <div id="unity-fullscreen-button"></div>
    </div>
</div>

<?php
/*$APPLICATION->IncludeComponent(
    "chazov:virtual.product",
    "",
    [
        "PRODUCT_ID" => $arResult['ID'],
        "IBLOCK_ID"  => $arParams['IBLOCK_ID']
    ]
);*/
?>