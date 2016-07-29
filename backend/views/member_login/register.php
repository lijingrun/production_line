<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/28
 * Time: 15:58
 */
?>
<script>
    function check_data(){
        var user_name = $("#user_name").val().trim();
        var phone = $("#phone").val().trim();
        var password1 = $("#password1").val().trim();
        var password2 = $("#password2").val().trim();
        if( user_name == '' || phone == '' || password1 == '' || password2 == ''){
            alert("请输入注册需要的所有信息！");
        }else if( password1 != password2){
            alert('2次输入的密码不一致，请重新输入！');
        }else{
            $("#form").submit();
        }
    }
</script>
<div style="padding:20px;">
    <form method="post" id="form">
    <p>
        您的称呼：<input type="text" name="user_name" id="user_name">
    </p>
        <input type="hidden" value="<?=\Yii::$app->request->csrfToken?>" name="_csrf" />
    <p>
        手机号码：<input type="text"  name="phone" id="phone"/>
    </p>
        <p>
            推荐号码：<input type="text" name="con_phone" placeholder="请填写推荐人的手机号码" />
        </p>
    <p>
        登录密码：<input type="password" name="password" id="password1" />
    </p>
    <p>
        确认密码：<input type="password" id="password2" />
    </p>
        <p>
            <input type="button" value="注册" class="btn-info" onclick="check_data();" />
        </p>
    </form>
</div>
