BX.ready(function () {
    const addAnswer = new BX.PopupWindow("product_3d", null, {
        content: BX('unity-container'),
        zIndex: 0,
        offsetLeft: 0,
        offsetTop: 0,
        draggable: {restrict: false},
    });

    const openModel = $('#openModel');

    openModel.click(function () {
        addAnswer.show(); // появление окна
    });

const container = document.querySelector("#unity-container");
const canvas = document.querySelector("#unity-canvas");
const loadingBar = document.querySelector("#unity-loading-bar");
const progressBarFull = document.querySelector("#unity-progress-bar-full");
const fullscreenButton = document.querySelector("#unity-fullscreen-button");
const warningBanner = document.querySelector("#unity-warning");

// Shows a temporary message banner/ribbon for a few seconds, or
// a permanent error message on top of the canvas if type=='error'.
// If type=='warning', a yellow highlight color is used.
// Modify or remove this function to customize the visually presented
// way that non-critical warnings and error messages are presented to the
// user.
function unityShowBanner(msg, type) {
}

var buildUrl = "/virtual-product/Build";
var loaderUrl = buildUrl + "/virtual-product.loader.js";
var config = {
    dataUrl: buildUrl + "/virtual-product.data",
    frameworkUrl: buildUrl + "/virtual-product.framework.js",
    codeUrl: buildUrl + "/virtual-product.wasm",
    streamingAssetsUrl: "StreamingAssets",
    companyName: "DefaultCompany",
    productName: "unimarket",
    productVersion: "0.1",
    showBanner: unityShowBanner,
};

//config.matchWebGLToCanvasSize = false;

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

    unityShowBanner('WebGL builds are not supported on mobile devices.');
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
        unityInstance.SendMessage('OnlyProduct', 'SetModelPath', window.__MODELPATH__);  /*<?= $arResult['MODEL_URL']?>*/
        fullscreenButton.onclick = () => {
            unityInstance.SetFullscreen(1);
        };
    }).catch((message) => {
        alert(message);
    });
};

document.body.appendChild(script);

});
