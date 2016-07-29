<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/13
 * Time: 9:03
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<script>
    function check_data(){
        var car_no = $("#car_no").val();
        if(car_no == '' || car_no == undefined){
            alert("请输入正确的车牌号码！");
        }else{
            $("#form").submit();
        }
    }
</script>
<div>
    <form method="post" id="form">
    <div>
        <p>请输入车牌号码：</p>
        <input type="text" name="car_no" id="car_no"/>
        <div>
            <input type="button" value="保存" onclick="check_data();" class="btn-success" />
        </div>
    </div>
    </form>
</div>


