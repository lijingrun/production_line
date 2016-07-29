<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/25
 * Time: 10:08
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
            url : 'index.php?r=goods/get_car_brand',
            success : function(data){
                $("#brand").append(data);
            }
        });
    }
    function get_models(){
        $("#model").remove();
        $("#style").remove();
        var brand_id = $("#brand_id").val();
        if(brand_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=goods/get_models',
                data : {'brand_id' : brand_id},
                success : function(data){
                    $("#brand_id").after(data);
                }
            });
        }
    }
    function get_style(){
        $("#style").remove();
        var model_id = $("#model").val();
        if(model_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=goods/get_car_style',
                data : {'model_id' : model_id},
                success : function(data){
                    $("#model").after(data);
                }
            });
        }
    }
</script>
<div>
    <form method="post">
        <div class="div-inside">
            车牌:<input type="text" value="<?php echo $car['car_no']?>" name="car_no">
        </div>
        <div class="div-inside">

            型号:
            <span id="brand">
            <?php
            if(empty($car['style_name'])){
                echo "未选择型号";
            }else{
                echo $car['brand_name'].'-'.$car['model_name'].'-'.$car['style_name'];
            }
            ?>
                <input type="button" value="修改/增加型号" onclick="change_brand();" />
            </span>
        </div>
        <div class="div-inside">
            购买年份:<input type="text" value="<?php echo $car['buy_year'];?>" name="buy_year">
        </div>
        <div class="div-inside">
            类型:
            <select name="car_type">
                <?php foreach($types as $type): ?>
                <option value="<?php echo $type['type_id']?>" <?php if($type['type_id'] == $car['car_type']){echo "selected"; }?> >
                    <?php echo $type['car_type']; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="div-inside">
            发动机类型：
            <select name="engine_type">
                <option value="1" <?php if($car['engine_type'] == 1){echo 'selected';}?>>自然吸气</option>
                <option value="2" <?php if($car['engine_type'] == 2){echo 'selected';}?>>电动发动机</option>
                <option value="3" <?php if($car['engine_type'] == 3){echo 'selected';}?>>涡轮增压</option>
            </select>
        </div>
        <div class="div-inside">
            车架码：<input type="text" value="<?php echo $car['car_code']?>" name="car_code" />
        </div>
        <div>
            车辆备注：
            <textarea name="remarks" cols="50" rows="5"><?php echo $car['remarks']  ?></textarea>
        </div>
        <div class="div-inside">
            <input type="submit" value="修改内容" />
        </div>
    </form>
</div>
