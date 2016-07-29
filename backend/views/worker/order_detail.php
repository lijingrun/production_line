<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/11
 * Time: 21:30
 */
?>
<script>
    function input_mileage(){
        var order_id = <?php echo $order['order_no']; ?>;
        var mileage = $("#mileage").val().trim();
        if(mileage == ''){
            alert("请输入维修时候的里程");
        }else{
            $.ajax({
                type : 'post',
                url : 'index.php?r=worker/input_mileage',
                data : {'order_id' : order_id, 'mileage' : mileage},
                success : function(data){
                    if(data == 111){
                        alert("操作成功！");
                        location.reload();
                    }else{
                        alert("系统繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
    function finish_order(){
        var order_id = <?php echo $order['order_no']; ?>;
        var reason = $("#reason").val();
        if(confirm("是否确定完成工单？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=worker/finish_order',
                data : {'order_id' : order_id, 'reason' : reason},
                success : function(data){
                    if(data == 111){
                        alert("您已申请完工，请联系相关人员进行完工审核！");
                        location.href='index.php';
                    }else if(data == 333){
                        alert("该订单已经超期，请填写超期原因！");
                        $("#reason_input").html("");
                        $("#reason_input").append("超时原因:<input type='text' id='reason'>");
                    }else{
                        alert("完成失败，请稍后重试！");
                    }
                }
            });
        }
    }
    function begin_to_work(){

        if(confirm("是否已经完成所有检查项？")){
            var order_id = <?php echo $order['order_no']; ?>;
            $.ajax({
                type : 'post',
                url : 'index.php?r=worker/begin_to_work',
                data : {'order_id' : order_id},
                success : function(data){
                    if(data == 111){
                        alert("操作成功，请开始施工！");
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
    function to_add_remarks(){
        $("#remarks_div3").remove();
        $("#remarks_div2").show();
    }
    function add_remarks(){
        var remarks = $("#remarks").val();
        var car_id = <?php echo $car['id']?>;
        $.ajax({
            type : 'post',
            url : 'index.php?r=worker/add_remarks',
            data : {'remarks' : remarks, 'car_id' : car_id},
            success : function(data){
                if(data == 111){
                    alert('添加成功！');
                    location.reload();
                }else{
                    alert("服务器繁忙，请稍后重试！");
                }
            }
        });
    }
    function change_remarks(){
        $("#remark_div1").remove();
        $("#remarks_div2").show();
    }
</script>
<script language="JavaScript">
    function myrefresh(){
        window.location.reload();
    }
    setTimeout('myrefresh()',100000); //指定100秒刷新一次
</script>
<?php if(!empty($order)){?>
<div>
    <h4>订单号：<?php echo $order['order_no']; ?></h4>
    <h4>车牌号：<?php echo $car['car_no']; ?></h4>
    <h4>里程：<input type="text" id="mileage" style="width:150px;" value="<?php echo $order['mileage'];?>" />km
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" value="确定" onclick="input_mileage();">
    </h4>
    <?php if(!empty($packages)){ ?>
        <h5>包含：</h5>
    <?php foreach($packages as $package): ?>
            <p style="color:red;"><?php echo $package['name'];?>套餐</p>
    <?php endforeach; ?>
    <?php } ?>
    <?php if($order['take_sp']){  ?>
    <h4 style="color:red;">客户需要取回更换下来的零件！</h4>
    <?php } ?>
    <?php if(!empty($car['remarks'])){ ?>
        <div id="remark_div1">
            <p><?php echo $car['remarks']?></p>
            <input type="button" value="修改备注" onclick="change_remarks();" />
        </div>
    <?php }else{ ?>
    <input type="button" value="添加备注" onclick="to_add_remarks();" id="remarks_div3" />
    <?php } ?>
    <div id="remarks_div2" style="display:none">
        <h4>液体容量备注：</h4>
        <textarea id="remarks" cols="40" rows="5"><?php echo $car['remarks']?></textarea>
        <p><input type="button" value="确定添加" onclick="add_remarks();" /></p>
    </div>
</div>
    <h4>服务：</h4>
    <?php foreach($orders as $val):?>
<div>
    <ul>
        <li><span style="font-weight:bold;font-size: 24px;"><?php echo $val['service_name']; ?></span></li>
    </ul>
</div>
    <?php endforeach;?>

        <?php if(!empty($order_goods)){ ?>
        <h4>材料：</h4>
        <ul>
            <?php foreach($order_goods as $goods):?>
                <li>
                    <span style="font-weight:bold;font-size: 24px;"><?php echo $goods['goods_name'].$goods['goods']['style']."(".$goods['goods']['spec'].")";?> ---X<?php echo $goods['nums'];?></span>
                    <?php if($goods['package_id'] != 0){ ?>
                    <span style="color:red">
                        <?php echo "(套餐内产品)" ?>
                    </span>
                    <?php } ?>
                </li>
            <?php endforeach;?>
        </ul>
            <?php } ?>
<div id="reason_input">

</div>
    <div>
        <h4>工单状态：
        <?php
        switch($order['status']){
            case 12 : echo "检测中（检测用时".$checked_time."分钟）";
                break;
            case 20 : echo "施工中(施工用时".$had_use_time."分钟）";
                break;
            default : echo "未知状态";
        }
        ?>
        </h4>
    </div>
    <?php if(!empty($types)){ ?>
    <div>
        检查项目：
        <ul>
            <?php foreach($types as $type): ?>
            <li><?php echo $type['check_type'];?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php } ?>
<div style="padding:10px;">
    <?php if($order['status'] == 12){ ?>
    <input type="button" value="开始维修" onclick="begin_to_work();" />
    <?php }else if($order['status'] == 20){ ?>
        <a href="index.php?r=worker/additional&order_no=<?php echo $order['order_no'];?>">
        <input type="button" value="提醒加单"  />
        </a>
    <input type="button" value="申请完工" onclick="finish_order();" />
    <?php } ?>
</div>
    <?php if(!empty($car_reasons)){ ?>
    <div>
        加单申请：
        <ul>
            <?php foreach($car_reasons as $reason): ?>
            <li><?php
                echo $reason['service_name']."[".date("Y-m-d H:i:s",$reason['create_time'])."]----";
                switch($reason['status']){
                    case 1 : echo "客户未确认";
                        break;
                    case 2 : echo "客户已加单";
                        break;
                    case 0 : echo "客户放弃加单";
                        break;
                }
                ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
        <?php }?>
<?php }else{?>
<h1>你暂时未有工单！</h1>
<?php }?>
