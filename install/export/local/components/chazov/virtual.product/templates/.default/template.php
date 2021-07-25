<?php CUtil::InitJSCore( array('ajax' , 'jquery' , 'popup' ));?>
<script type="text/javascript">
    BX.ready(function(){

        var addAnswer = new BX.PopupWindow("my_answer", null, {
            content: BX('ajax-add-answer'),
            closeIcon: {right: "20px", top: "10px"},
            titleBar: {content: BX.create("span", {html: '<b>3D Модель</b>', 'props': {'className': 'access-title-bar'}})},
            zIndex: 0,
            offsetLeft: 0,
            offsetTop: 0,
            draggable: {restrict: false},
        });
        $('#click_test').click(function(){
            /*BX.ajax.insertToNode('/include/testform.php', BX('ajax-add-answer'));*/ // функция ajax-загрузки контента из урла в #div
            addAnswer.show(); // появление окна
        });
    });
</script>

<a id="click_test" href="javascript:void(0)" >Клик</a>
<div id="ajax-add-answer"></div>