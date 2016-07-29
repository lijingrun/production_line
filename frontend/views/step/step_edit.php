<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/7/28
 * Time: 16:40
 */
?>
<script type="text/javascript" charset="utf-8" src="ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="ueditor/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="ueditor/lang/zh-cn/zh-cn.js"></script>
<script>

</script>
<div>
    <div style="padding:10px;font-size: 20px;" align="center">
        组件名称：<input type="text" id="title" value="<?php echo $step['title']?>" />
    </div>
    <div>
        <h4>内容：</h4>
        <script id="editor" type="text/plain" style="width:1024px;height:500px;"></script>
    </div>
</div>
<button onclick="getContent()">保存</button>
<button onclick="del_step(<?php echo $step['step_id']?>)" >删除组件</button>
<button onclick="setContent()" id="init">写入内容</button>


<script type="text/javascript">

    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor');


    function isFocus(e){
        alert(UE.getEditor('editor').isFocus());
        UE.dom.domUtils.preventDefault(e)
    }
    function setblur(e){
        UE.getEditor('editor').blur();
        UE.dom.domUtils.preventDefault(e)
    }
    function insertHtml() {
        var value = prompt('插入html代码', '');
        UE.getEditor('editor').execCommand('insertHtml', value)
    }
    function createEditor() {
        enableBtn();
        UE.getEditor('editor');
    }
    function getAllHtml() {
        alert(UE.getEditor('editor').getAllHtml())
    }
    function getContent() {
        var arr = [];
        var title = $("#title").val().trim();
        if(title == ''){
            alert("请输入标题！");
            return false;
        }
        arr.push(UE.getEditor('editor').getContent());
        var content = arr.join("\n");
        var step_id = <?php echo $step['step_id'];?>;
        $.ajax({
            type : 'post',
            url : 'index.php?r=step/add_ajax',
            data : {'content' : content, 'title' : title, 'step_id' : step_id},
            success : function(data){
                if(data == 111){
                    alert("操作成功！");
                    location.href="index.php?r=step";
                }else{
                    alert("服务器繁忙，请稍后重试！");
                }
            }
        });
    }
    function getPlainTxt() {
        var arr = [];
        arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getPlainTxt());
        alert(arr.join('\n'))
    }
    function setContent(isAppendTo) {
        var arr = [];
        var step_id = <?php echo $step['step_id'];?>;
        $.ajax({
            type : 'post',
            url : 'index.php?r=step/get_content',
            data : {'step_id' : step_id},
            success : function(data){
                UE.getEditor('editor').setContent(data, isAppendTo);
//                alert(arr.join("\n"));
            }
        });
    }
    function setDisabled() {
        UE.getEditor('editor').setDisabled('fullscreen');
        disableBtn("enable");
    }

    function setEnabled() {
        UE.getEditor('editor').setEnabled();
        enableBtn();
    }

    function getText() {
        //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
        var range = UE.getEditor('editor').selection.getRange();
        range.select();
        var txt = UE.getEditor('editor').selection.getText();
        alert(txt)
    }

    function getContentTxt() {
        var arr = [];
        arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
        arr.push("编辑器的纯文本内容为：");
        arr.push(UE.getEditor('editor').getContentTxt());
        alert(arr.join("\n"));
    }
    function hasContent() {
        var arr = [];
        arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
        arr.push("判断结果为：");
        arr.push(UE.getEditor('editor').hasContents());
        alert(arr.join("\n"));
    }
    function setFocus() {
        UE.getEditor('editor').focus();
    }
    function deleteEditor() {
        disableBtn();
        UE.getEditor('editor').destroy();
    }
    function disableBtn(str) {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            if (btn.id == str) {
                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
            } else {
                btn.setAttribute("disabled", "true");
            }
        }
    }
    function enableBtn() {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
        }
    }

    function getLocalData () {
        alert(UE.getEditor('editor').execCommand( "getlocaldata" ));
    }

    function clearLocalData () {
        UE.getEditor('editor').execCommand( "clearlocaldata" );
        alert("已清空草稿箱")
    }

    function del_step(step_id){
        if(confirm("删除后不能恢复，是否确定删除该组件？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=step/del',
                data : {'step_id' : step_id},
                success : function(data){
                    if(data == 111){
                        alert("删除成功！");
                        location.href="index.php?r=step";
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
    var t=setTimeout("setContent()",100);
</script>