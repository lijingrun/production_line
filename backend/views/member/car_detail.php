<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/25
 * Time: 10:54
 */
?>
<style>
    .div-inside{
        padding-top:20px;
    }
</style>
<script>
    function change_brand(){
        $("#brand").html('');
        $.ajax({
            type : 'post',
            url : 'index.php?r=member/get_car_brand',
            success : function(data){
                $("#brand").append(data);
            }
        });
    }
    function get_models(){
        $("#model").remove();
        var brand_id = $("#brand_id").val();
        if(brand_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=member/get_models',
                data : {'brand_id' : brand_id},
                success : function(data){
                    $("#brand_id").after(data);
                }
            });
        }
    }
    function get_style(){
        var model_id = $("#model").val();
        $("#style_list").remove();
        if(model_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=member/get_style',
                data : {'model_id' : model_id},
                success : function(data){
                    $("#model").after(data);
                }
            });
        }
    }
    function check_data(){
        var car_no = $("#car_no").val();
        var style = $("#style").val();
        var has_style = $("#has_style").val();
        if( car_no == ''){
            alert("请填写正确的车牌号码！");
        }else if((style == '' || style == undefined) && has_style != 1){
            alert("请选择车辆型号！");
        }else{
            $("#form").submit();
        }
    }
</script>
<div>

        <div class="panel panel-success" style="font-size: 18px;margin-top: 20px;">
        <?php if(!empty($message)){?>
        <span style="color:red;"><?php echo $message;?></span>
        <?php }?>
            <div style="padding:10px;">
        <form method="post" id="form">
            <div class="div-inside">
                车牌:<input type="text" value="<?php echo $car['car_no']?>" id="car_no" name="car_no" />
            </div>
            <div class="div-inside">

                <p>型号:</p>
            <span id="brand">
            <?php
            if(empty($car['style_name'])){
                echo "未选择型号";
            }else{
                echo $car['brand_name'].'-'.$car['model_name'].'-'.$car['style_name'];
                echo "<input type='hidden' value='1' id='has_style'>";
            }
            ?>
                <input type="button" value="修改/增加型号" onclick="change_brand();" class="btn-info" />
            </span>
            </div>
            <div class="div-inside">
                发动机类型：
                <select name="engine_type">
                    <option value="1" <?php if($car['engine_type'] == 1){echo 'selected';}?>>自然吸气</option>
                    <option value="2" <?php if($car['engine_type'] == 2){echo 'selected';}?>>电动发动机</option>
                    <option value="3" <?php if($car['engine_type'] == 3){echo 'selected';}?>>涡轮增压</option>
                </select>
            </div>
            <div class="input-group" style="margin-top: 20px;">
                <span class="input-group-addon" id="basic-addon1">购买年份:</span>
                <input type="text" value="<?php echo $car['buy_year'];?>" name="buy_year" class="form-control">
            </div>
            <div class="input-group" style="margin-top: 20px;">
                <span class="input-group-addon" id="basic-addon1">车架码:</span>
                <input type="text" value="<?php echo $car['car_code'];?>" name="car_code" class="form-control">
            </div>
<!--            <div class="div-inside">-->
<!--                类型:-->
<!--                <select name="car_type">-->
<!--                    --><?php //foreach($types as $type): ?>
<!--                        <option value="--><?php //echo $type['type_id']?><!--" --><?php //if($type['type_id'] == $car['car_type']){echo "selected"; }?><!-- >-->
<!--                            --><?php //echo $type['car_type']; ?>
<!--                        </option>-->
<!--                    --><?php //endforeach; ?>
<!--                </select>-->
<!--            </div>-->
            <div class="div-inside">
                <?php if(empty($car['car_no'])){ ?>
                    <input type="button" value="增加" class="btn-success" onclick="check_data();"/>
                <?php }else{ ?>
                    <input type="button" value="确定修改" class="btn-success" onclick="check_data();"/>
                <?php } ?>
            </div>
        </form>
            </div>
        </div>
</div>
