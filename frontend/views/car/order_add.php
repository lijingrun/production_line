<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/9
 * Time: 20:29
 */

?>
<script>
    function find_service(){
        var type_id = $("#service_type").val();
        $("#service_list").html('');
        if(type_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=car/find_service',
                data : {'type_id' : type_id},
                success : function(data){
                    $("#service_list").append(data);
                }
            });
        }
    }
    function get_goods(){
        $("#goods_list").html('');
        var service_id = $("#service_id").val();
        var car_id = $("#car_id").val();
        if(service_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=car/get_goods',
                data : {'service_id' : service_id, 'car_id' : car_id},
                success : function(data){
                    $("#goods_list").append(data);
                }
            });
        }
    }
    function check_data(){
        var car_mileage = $("#car_mileage").val();
        var service_id = $("#service_id").val();
        if(car_mileage == '' || service_id == 0 || service_id == undefined){
            alert("请填写里程/选择服务类型");
        }else{
            $("#form").submit();
        }
    }
</script>
<form method="post" id="form">
    <div>
    <?php if(empty($car['car_code'])){?>
        车架码：<input type="text" name="car_code" /><span style="color:red">*该车还未登记车架码，请登记*</span>
    <?php }?>
    </div>
    <div>
        <h4>车牌号：<?php echo $car['car_no']; ?></h4>
        <input type="hidden" value="<?php echo $car['id']; ?>" name="car_id"  id="car_id"/>
        里程：<input type="text" name="car_mileage" id="car_mileage" value="<?php echo $mileage; ?>" />km
    </div>
    <div id="service_types_list" style="padding-top: 10px;">
        <select id="service_type" onchange="find_service();">
            <option value="0" >请选择类型</option>
            <?php foreach($service_types as $val): ?>
            <option value="<? echo $val['type_id']?>"><?php echo $val['name']?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        取回更换下来的零件
        <select name="take_sp">
            <option value="0">不取回</option>
            <option value="1">取回</option>
        </select>
    </div>
    <div id="service_list" style="padding-top:10px;">
<!--        <select name="service_id" id="service_id" onchange="get_goods();">-->
<!--            <option value="0">请选择服务类型</option>-->
<!--            --><?php //foreach($services as $service): ?>
<!--            <option value="--><?php //echo $service['id']; ?><!--">--><?php //echo $service['name']?><!--</option>-->
<!--            --><?php //endforeach;?>
<!--        </select>-->
    </div>
    <div id="goods_list"></div>
    <div>
        <input type="button" value="提交订单" onclick="check_data();" />
    </div>
</form>
