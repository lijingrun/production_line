<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 17:18
 */
use yii\widgets\LinkPager;

?>
<style>
    li{
        float: left;
        list-style-type:none;
    }
    .service_div{
        background-color: #2a6496;
        margin:10px;
        padding:20px;
        width:180px;
        color:white;
    }
</style>
<script>
    function find_service() {
        var type_id = $("#type_id").val();
        location.href="index.php?r=service&type_id="+type_id;
    }
    function to_edit_name(id){
        var htm = "<input type='text' onblur='edit_name("+id+");' id='new_name' style='color:black;' />";
        $("#edit_name"+id).removeAttr('onclick');
        $("#edit_name"+id).html(htm);
        $("#name").focus();
    }
    function edit_name(id){
        var new_name = $("#new_name").val();
        if(new_name == ''){
            alert("请输入新的服务名称");
        }else{
            $.ajax({
                type : 'post',
                url : 'index.php?r=service/edit_name',
                data : {'new_name' : new_name, 'service_id' : id},
                success : function(data){
                    if(data == 111){
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试");
                    }
                }
            });
        }
    }
    function to_edit_time(id){
        var htm = "<input type='text' style='width:30px;color:black' onblur='edit_time("+id+");' id='new_time' >";
        $("#time"+id).removeAttr('onclick');
        $("#time"+id).html(htm);
    }
    function edit_time(id){
        var new_time = $("#new_time").val();
        if(new_time == ''){
            alert("请输入时间");
        }else{
            $.ajax({
                type : 'post',
                url : 'index.php?r=service/edit_time',
                data : {'id' : id, 'new_time' : new_time},
                success : function(data){
                    if(data == 111){
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
</script>
<div>
    <a href="index.php?r=service/service_type">服务类型</a>
    <a href="index.php?r=service/service_add">添加新服务</a>
    <a href="index.php?r=car/type_add">添加汽车类型</a>
    <a href="index.php?r=service/check_type">快速检测项</a>
</div>
<div>
    服务类型：
    <select id="type_id">
        <option value="0">全部</option>
        <?php foreach($types as $type): ?>
        <option value="<?php echo $type['type_id'];?>" <?php if($type_id == $type['type_id']){ echo 'selected'; }?>>
            <?php echo $type['name']; ?>
        </option>
        <?php endforeach; ?>
    </select>
    <input type="button" value="查找" onclick="find_service();" />
</div>
<div>
    <ul>
    <?php foreach($services as $service): ?>
    <li>
        <div class="service_div">
            <p style="height: 40px;" id="edit_name<?php echo $service['id']?>" onclick="to_edit_name(<?php echo $service['id'];?>);">
                <?php echo $service['name']?>
            </p>
            <p>标准时限：<span id="time<?php echo $service['id'];?>" onclick="to_edit_time(<?php echo $service['id'];?>);"><?php echo $service['use_time']."分钟"; ?></span></p>
            <p>
                <a href="index.php?r=service/service_goods&id=<?php echo $service['id'];?>" style="color:white">
                    包含产品
                </a>
            </p>
            <p>
                <a href="index.php?r=service/worker_conten&id=<?php echo $service['id']?>">
                    工时费介绍
                </a>
            </p>
<!--            <p>-->
<!--                <a href="index.php?r=service/service_price&id=--><?php //echo $service['id'];?><!--" style="color:white">-->
<!--                    工费设置-->
<!--                </a>-->
<!--            </p>-->
            <p>自检间隔:<span><?php echo empty($service['check_km']) ? '未设置' : $service['check_km']."km";;?></span></p>
            <p><a href="index.php?r=service/edit&id=<?php echo $service['id'];?>"><input type='button' value='修改' /></a></p>
        </div>
    </li>
    <?php endforeach; ?>
    </ul>
    <?= LinkPager::widget(['pagination' => $pages]); ?>
</div>