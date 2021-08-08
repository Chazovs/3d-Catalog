<?php
/** @var $arResult */
/** @var $arParams */
?>

<link rel="shortcut icon" href="/virtual-market/TemplateData/favicon.ico">
<link rel="stylesheet" href="/virtual-market/TemplateData/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<script>
    function getCatalog() {
        return <?= json_encode($arResult['CATALOG']) ?>;
    }
</script>

<div id="unity-container" class="unity-desktop">
    <canvas id="unity-canvas" width=960 height=600></canvas>
    <div id="unity-loading-bar">
        <div id="unity-logo"></div>
        <div id="unity-progress-bar-empty">
            <div id="unity-progress-bar-full"></div>
        </div>
    </div>
    <div id="unity-warning"> </div>
    <div id="unity-footer">
        <div id="unity-fullscreen-button"></div>
    </div>
</div>
<script>
    const container = document.querySelector("#unity-container");
    const canvas = document.querySelector("#unity-canvas");
    const loadingBar = document.querySelector("#unity-loading-bar");
    const progressBarFull = document.querySelector("#unity-progress-bar-full");
    const fullscreenButton = document.querySelector("#unity-fullscreen-button");

    var buildUrl = "/virtual-market/Build";
    var loaderUrl = buildUrl + "/virtual-market.loader.js";
    var config = {
        dataUrl: buildUrl + "/virtual-market.data",
        frameworkUrl: buildUrl + "/virtual-market.framework.js",
        codeUrl: buildUrl + "/virtual-market.wasm",
        streamingAssetsUrl: "StreamingAssets",
        companyName: "DefaultCompany",
        productName: "unimarket",
        productVersion: "0.1",
    };

    // By default Unity keeps WebGL canvas render target size matched with
    // the DOM size of the canvas element (scaled by window.devicePixelRatio)
    // Set this to false if you want to decouple this synchronization from
    // happening inside the engine, and you would instead like to size up
    // the canvas DOM size and WebGL render target sizes yourself.
    // config.matchWebGLToCanvasSize = false;

    if (/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
        // Mobile device style: fill the whole browser client area with the game canvas:

        var meta = document.createElement('meta');
        meta.name = 'viewport';
        meta.content = 'width=device-width, height=device-height, initial-scale=1.0, user-scalable=no, shrink-to-fit=yes';
        document.getElementsByTagName('head')[0].appendChild(meta);
        container.className = "unity-mobile";

        // To lower canvas resolution on mobile devices to gain some
        // performance, uncomment the following line:
        // config.devicePixelRatio = 1;

        canvas.style.width = window.innerWidth + 'px';
        canvas.style.height = window.innerHeight + 'px';
    } else {
        // Desktop style: Render the game canvas in a window that can be maximized to fullscreen:

        canvas.style.width = "960px";
        canvas.style.height = "600px";
    }

    loadingBar.style.display = "block";

    var script = document.createElement("script");
    script.src = loaderUrl;
    script.onload = () => {
        createUnityInstance(canvas, config, (progress) => {
            progressBarFull.style.width = 100 * progress + "%";
        }).then((unityInstance) => {
            loadingBar.style.display = "none";
            unityInstance.SendMessage('Main', 'SetBxSessId', BX.bitrix_sessid());
            unityInstance.SendMessage('Main', 'SetSiteId', BX.message('SITE_ID'));
            unityInstance.SendMessage('Main', 'SetConfirmOrderUrl', '<?= $arParams["ConfirmOrderUrl"] ?>');
            unityInstance.SendMessage('Main', 'SetServerName', window.location.host);
            fullscreenButton.onclick = () => {
                unityInstance.SetFullscreen(1);
            };
        }).catch((message) => {
            alert(message);
        });
    };
    document.body.appendChild(script);
</script>
