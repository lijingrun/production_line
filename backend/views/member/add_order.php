<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/13
 * Time: 15:21
 */
?>
<script>
    function input_nums(id){
        var htm = "<p style='font-size:20px;'>需要数量：<input style='width:50px;' type='text' value='1' name='goods_nums[]' /></p>";
        if($("#check_box"+id).is(":checked")){
            $("#input"+id).html(htm);
        }else{
            $("#input"+id).html('');
        }

    }
    function find_service(){
        var type_id = $("#service_type").val();
        $("#service_list").html('');
        $("#goods_list").html('');
        if(type_id != 0){
            $.ajax({
                type : 'post',
                async : false,
                url : 'index.php?r=member/find_service',
                data : {'type_id' : type_id},
                success : function(data){
                    $("#service_list").append(data);
                }
            });
        }
    }
    function find_goods(){
        var service_id = $("#service_id").val();
        var car_id = $("#car_id").val();
        $("#goods_list").html('');
        if(service_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=member/get_goods',
                data : {'service_id' : service_id, 'car_id' : car_id},
                success : function(data){
                    $("#goods_list").append(data);
                }
            });
        }
    }
    function check_data(){
        var car_id = $("#car_id").val();
        var service_id = $("#service_id").val();
        var mileage = $("#mileage").val().trim();
        if(car_id == 0 || service_id == 0 || mileage == '' || service_id == undefined){
            alert("你未选择具体服务/未填写实时公里数");
        }else{
            $("#form").submit();
        }
    }
    function find_worder_content(){
        $("#worker_content_list").remove();
        var service_id = $("#service_id").val();
        if(service_id != 0 && service_id != undefined) {
            $.ajax({
                type: 'post',
                url: 'index.php?r=member/worker_content',
                data: {'service_id': service_id},
                success: function (data) {
                    $("#worker_content").val('隐藏');
                    $("#worker_content").attr("onclick",'remove_worker_content();')
                    $("#worker_content").after(data);
                }
            });
        }else{
            alert("请先选择具体服务！");
        }
    }
    function remove_worker_content(){
        $("#worker_content").val('查看工时费详情');
        $("#worker_content").attr("onclick",'find_worder_content();')
        $("#worker_content_list").remove();
    }
</script>
<div >
<form method="post" id="form">
    <div style="padding:10px 0px 10px 0px;font-size:20px;">
        <?php if(count($my_cars) == 1){?>
        <span style="font-size:25px;"><?php echo $car['car_no']; ?></span>
        <input type="hidden" value="<?php echo $car['id']?>" name="car_id" id="car_id"/>
        <?php }else{?>
        <select name="car_id" id="car_id">
            <option value="0">请选择车辆</option>
            <?php foreach($my_cars as $val):?>
                <option value="<?php echo $val['id']; ?>" <?php if($val['id'] == $car['id']){ echo "selected";}?> ><?php echo $val['car_no']; ?></option>
            <?php endforeach;?>
        </select>
        <?php }?>
            <input type="hidden" name="mileage" placeholder="请填写当前汽车里程" id="mileage" value="<?php echo empty($mileage)? 0 : $mileage;?>">
        <?php if($need_code){ ?>
        <div style="padding-top:20px;">
            <input type="text" name="car_code" placeholder="车架码" />
            <p style="color:red;font-size: 15px;">*为了我们能够更好地帮您寻找适合您爱车的零件，建议您输入车架码*</p>
        </div>
        <?php } ?>
    </div>
    <div>
        完工后取回换下来的零件
        <select name="take_sp">
            <option value="0">不需要</option>
            <option value="1" <?php if($order['take_sp'] == 1){echo "selected=selected";}?>>需要</option>
        </select>
    </div>
    <div id="service_types_list" style="padding-top: 10px;font-size:20px;">
        <select id="service_type" onchange="find_service();">
            <option value="0" >请选择服务类型</option>
            <?php foreach($service_types as $val): ?>
                <option value="<? echo $val['type_id']?>"><?php echo $val['name']?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div id="service_list" style="padding-top:10px;font-size: 20px;">
    </div>
    <div id="goods_list" class="panel panel-danger" id="goods_list">

    </div>
    <div style="font-size:20px;padding-top:20px;padding-bottom: 30px;;" >
        <input type="button" value="提交" onclick="check_data();" class="btn-danger"/>
    </div>
<!--    <div style="font-size: 18px;color:red;padding-bottom: 20px;">-->
<!--        *为了更好地记录你的保养记录，请填写准确的当前里程*-->
<!--    </div>-->
</form>
</div>
<script type="text/javascript" src="js/jquery-1.10.2.js">

</script>
<input type="hidden" value="<?php echo $service_type['type_id'];?>" id="r_type_id" />
<input type="hidden" value="<?php echo $service['id'];?>" id="r_service_id" />
<script>
    $(function(){
        var r_type_id = $("#r_type_id").val();
        var r_service_id = $("#r_service_id").val();
        if(r_type_id > 0){
            $("#service_type").val(r_type_id);
            $("#service_type").change();
        }
        if(r_service_id > 0){
            $("#service_id").val(r_service_id);
            $("#service_id").change();
        }
    });
</script>