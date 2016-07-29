<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/28
 * Time: 8:49
 */
?>
<script>
    function member_login(){
        var phone = $("#phone").val();
        var password = $("#password").val();
        if(phone == '' || password == ''){
            alert("请输入相关登录信息");
        }else{
            $.ajax({
                type : 'post',
                url : 'index.php?r=member_login/login_in',
                data : {'phone' : phone, 'password' : password, '_csrf':'<?=\Yii::$app->request->csrfToken?>'},
                success : function(data){
                    if(data == 111){
                        location.href = 'index.php?r=member'
                    }else{
                        alert("登录失败，请确认登录信息！");
                    }
                }
            });
        }
    }
</script>
<div>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">请登录</h3>
        </div>
        <div class="panel-body">
            <form method="post">
                <?php if(!empty($error_message)){ ?>
                    <div class="alert alert-warning" role="alert">
                        <strong><?php echo $error_message;?></strong>
                    </div>
                <?php } ?>
            <p>电话号码：<input type="text" name="phone" value="<?php echo $phone;?>" /></p>
            <p>登录密码：<input type="password" name="password" /></p>
            <input type="submit" value="登录" class="btn-success" />
            <a href="index.php?r=member_login/register">
            <input type="button" value="注册" class="btn-primary"  />
            </a>
            </form>
        </div>
    </div>
</div>
