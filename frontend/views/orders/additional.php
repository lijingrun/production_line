<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/5
 * Time: 8:56
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
        var car_id = <?php echo $car['id']?>;
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
            alert("请选择服务类型");
        }else{
            $("#form").submit();
        }
    }
</script>

<div>
    <h3>
        加单车牌：<?php echo $car['car_no'];?>
    </h3>
    <div>
        <form method="post" id="form">
        <div id="service_types_list" style="padding-top: 10px;">
            <select id="service_type" onchange="find_service();">
                <option value="0" >请选择类型</option>
                <?php foreach($service_types as $val): ?>
                    <option value="<? echo $val['type_id']?>"><?php echo $val['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div id="service_list" style="padding-top:10px;">
        </div>
        <div id="goods_list"></div>
            <input type="button" value="确定加单" onclick="check_data()" />
        </form>
    </div>
</div>
