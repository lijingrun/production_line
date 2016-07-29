<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/9
 * Time: 22:35
 */
use yii\widgets\LinkPager;

?>
<script>
    function find_orders(){
        var order_no = $("#order_no").val();
        var status = $("#status_id").val();
		var begin_date = $("#begin_date").val();
		var end_date = $("#end_date").val();
        location.href = "index.php?r=orders&order_no="+order_no+"&status="+status+"&begin_date="+begin_date+"&end_date="+end_date;
    }
    function export_orders(){
        var order_no = $("#order_no").val();
        var status = $("#status_id").val();
        var begin_date = $("#begin_date").val();
        var end_date = $("#end_date").val();
        location.href = "index.php?r=orders/export_orders&order_no="+order_no+"&status="+status+"&begin_date="+begin_date+"&end_date="+end_date;
    }
    function del_order(id){
        if(confirm("是否确定取消订单？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=orders/del_order',
                data : {'order_id' : id},
                success : function(data){
                    if(data == 333){
                        alert("没有权限！");
                    }else if(data == 111){
                        alert("取消成功！");
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
    function get_order(order_no){
        $.ajax({
            type : 'post',
            url : 'index.php?r=orders/get_order',
            data : {'order_no' : order_no},
            success : function(data){
                if(data == 111){
                    alert("操作成功！");
                    location.reload();
                }else if(data == 333){
                    alert("该工单超时，请填写超时原因！");

                }else{
                    alert("系统繁忙，请稍后重试！");
                }
            }
        });
    }
    function get_order(order_no){
        //播放音频
        var myAuto = document.getElementById('myaudio');
        myAuto.play();
        $.ajax({
            type : 'post',
            url : 'index.php?r=orders/get_order',
            data : {'order_no' : order_no},
            success : function(data){
                if(data == 111){
                    alert("操作成功！");
                    location.reload();
                }else if(data == 333){
                    alert("该工单超时，请填写超时原因！");

                }else{
                    alert("系统繁忙，请稍后重试！");
                }
            }
        });
    }
    function change_price(order_id){
        var html = "<input type='text' style='width:50px;' id='c_price"+order_id+"' onblur='p_price("+order_id+");'>";
        $("#price"+order_id).html(html);
        $("#c_price"+order_id).focus();
    }
    function p_price(order_id){
        var price = $("#c_price"+order_id).val();
        $.ajax({
            type : 'post',
            url : 'index.php?r=orders/change_price',
            data : {'price' : price, 'order_id' : order_id},
            success : function(data){
                if(data == 111){
                    alert("修改成功！");
                    location.reload();
                }else{
                    alert('服务器繁忙，请稍后重试！');
                }
            }
        });
    }
    function check_time(order_no){
        $.ajax({
            type : 'post',
            url : 'index.php?r=orders/check_time',
            data : {'order_no' : order_no},
            success : function(data){
                alert(data);
            }
        });
    }
    function get_car(order_no){
        if(confirm("是否已经交车？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=orders/get_car',
                data : {'order_no' : order_no},
                success : function(data){
                    if(data == 111){
                        alert("交车成功！");
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
</script>
<script language="JavaScript">
    function myrefresh(){
        window.location.reload();
    }
    setTimeout('myrefresh()',10000); //指定10秒刷新一次
</script>
<style>
    td{
        padding-top: 18px;;
    }
</style>
<div align="center">
    订单号：<input type="text" id="order_no" value="<?php echo $order_no?>">
    订单类型：
    <select id="status_id">
        <option value="0">全部</option>
        <option value="11" <?php if($order_status == 11){ echo 'selected'; }?>>待开工</option>
        <option value="20" <?php if($order_status == 20){ echo 'selected'; }?>>施工中</option>
        <option value="30" <?php if($order_status == 30){ echo 'selected'; }?>>已完工</option>
        <option value="40" <?php if($order_status == 40){ echo 'selected'; }?>>已付款</option>
        <option value="50" <?php if($order_status == 50){ echo 'selected'; }?>>已评价</option>
        <option value="90" <?php if($order_status == 90){ echo 'selected'; }?>>已取消</option>
    </select>
	下单时间：
	<input type='date' id='begin_date' value="<?php echo $begin_date;?>"/>
	到
	<input type='date' id='end_date' value="<?php echo $end_date;?>"/>
    <input type="button" value="查询" onclick="find_orders();" />
    <a href="index.php?r=orders">
        <input type="button" value="重置" />
    </a>
    <a href="index.php?r=orders/appointment">
        <input type="button" value="预约订单" />
    </a>
    <a href="index.php?r=orders/order_back">
        <?php
            if($back_count > 0){
                echo "<span style='color:red;'>返工申请（".$back_count."）</span>";
            }else{
                echo "<input type='button' value='返工申请' />";
            }
        ?>
    </a>
    <a href="index.php?r=orders/additional">
        <input type="button" value="加单申请" />
    </a>
</div>
<div style="padding-top:20px;">
    <div align="right" style="padding-right:50px;">
    <input type="button" value="导出" onclick="export_orders()" class="btn-success" />
    </div>
    <table>
        <tr>
            <th style="width:120px;">工单号</th>
            <th style="width:100px;">车牌号</th>
            <th style="width:150px;">服务</th>
            <th style="width:120px;">建单时间</th>
            <th style="width:120px;">开始检测时间</th>
            <th style="width:120px;">开始施工时间</th>
            <th style="width:80px;;">工人</th>
            <th style="width:100px;">施工用时</th>
            <th style="width:80px;">工时费</th>
			<th style='width:80px;'>材料费</th>
			<th style="width:80px;">总金额</th>
            <th style="width:80px;">工单状态</th>
            <th style="width:80px;">操作</th>
        </tr>
        <?php foreach($orders as $order): ?>
        <tr>
            <td>
<!--                <a href="index.php?r=orders/detail&order_no=--><?php //echo $order['order_no']; ?><!--">-->
                <a href="index.php?r=orders/detail&order_id=<?php echo $order['id'];?>">
                <?php echo $order['order_no'];?>
                </a>
            </td>
            <td>
                <a href="index.php?r=car/detail&car_id=<?php echo $order['car_id'];?>">
                <?php echo $order['car']['car_no'];?>
                </a>
            </td>
            <td><?php echo $order['service_name'];?></td>
            <td>
			<p title='<?php echo date("H:m:s",$order['create_time']);?>'><?php echo date("Y-m-d",$order['create_time']);?></p>
			</td>
            <td><?php echo empty($order['checked_time']) ? '排队中' : "<p title=".date("H:i:s",$order['checked_time']).">".date("Y-m-d",$order['checked_time'])."</p>";?></td>
            <td><?php echo empty($order['begin_time']) ? '' :"<p title=".date("H:i:s",$order['begin_time']).">".date("Y-m-d",$order['begin_time'])."</p>"?></td>
            <td>
                <?php echo $order['worker']['username']; ?>
            </td>
            <td>
                <?php
                    if($order['status'] > 11 &&  !empty($order['begin_time'])){
                        if(empty($order['finish_time'])){
                            echo ceil((time()-$order['begin_time'])/60);
                        }else{
                            echo ceil(($order['finish_time']-$order['begin_time'])/60);
                        }
                        echo "分钟";
                    }
                ?>
            </td>
            <td>
                <?php if($order['status'] < 40){ ?>
                <span ondblclick="change_price(<?php echo $order['id'];?>);" id="price<?php echo $order['id'];?>"><?php echo empty($order['price']) ? '0.00' : $order['price'] ?></span>
                <?php }else{ ?>
                <span><?php echo empty($order['price']) ? '0.00' : $order['price'] ?></span>
                <?php } ?>
            </td>
			<td>
				<?php echo "￥".$order['goods_price']; ?>
			</td>
			<td>
				<?php echo "￥".$order['total_price']; ?>
			</td>
            <td>
                <?php
                    switch($order['status']){
                        case 11 : echo '待开工';
                            break;
                        case 12 : echo '检测中';
                            break;
                        case 20 : echo '施工中';
                            break;
                        case 21 : echo '待审验';
                            break;
                        case 30 : echo '已完工';
                            break;
                        case 40 :
                            if(empty($order['get_time'])){
                                echo '已付款';
                            }else{
                                echo "<span title='".date('Y-m-d H:i:s  ',$order['get_time'])."'>已交车</span>";
                            }
                            break;
                        case 50 : echo "<a href='index.php?r=orders/evaluate&id=".$order['order_no']."'>已评价</a>";
                            break;
                        case 90 : echo '已取消';
                            break;
                        case 10 : echo '预约单';
                            break;
                    }
                ?>
            </td>
            <td>
                <?php if($order['status'] == 11 || $order['status'] == 12   ){?>
                <a href="#" ><span onclick="del_order(<?php echo $order['id'];?>);">取消</span></a>
                    <input type="button" value="排队时间" onclick="check_time(<?php echo $order['order_no'];?>);" class="btn-success" />
                <?php }else if($order['status'] == 30){?>
                <a href="index.php?r=orders/to_payment&order_no=<?php echo $order['order_no'];?>">结账</a>
                <?php }else if($order['status'] == 10){?>
                <a href="#"><span onclick="get_order(<?php echo $order['order_no'];?>);" >接单</span></a>
                    <audio id="myaudio" src="tist.wav" controls="controls" loop="false" hidden="true"  >
                    </audio>
                <?php }else if($order['status'] == 20){?>
<!--                <a href="index.php?r=orders/additional&order_no=--><?php //echo $order['order_no']?><!--" >加单</a>-->
                <?php }else if($order['status'] == 40 && empty($order['get_time'])){?>
                    <input type="button" value="交车" onclick="get_car(<?php echo $order['order_no']?>)" class="btn-success" />
                <?php } ?>
            </td>
        </tr>
        <?php endforeach;?>
        <tr>
            <td colspan="5">
                <?= LinkPager::widget(['pagination' => $pages]); ?>
            </td>
        </tr>
    </table>
</div>
