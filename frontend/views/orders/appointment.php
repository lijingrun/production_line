<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/19
 * Time: 9:34
 */
?>
<script>
    function get_order(order_no){
        $.ajax({
            type : 'post',
            url : 'index.php?r=orders/get_order',
            data : {'order_no' : order_no},
            success : function(data){
                if(data == 111){
                    alert("操作成功！");
                    location.href="index.php?r=orders";
                }else if(data == 333){
                    alert("工单不存在！");

                }else{
                    alert("系统繁忙，请稍后重试！");
                }
            }
        });
    }
</script>
<form method="post">
<!--<p>预约订单号<input type="text" name="order_no" value="--><?php //echo $order_no; ?><!--"></p>-->
<p>预约车牌号：<input type="text" name="car_no" value="<?php echo $car_no; ?>"></p>
<input type="submit" value="查询" />
</form>

<?php if(empty($orders)){ ?>
<p style="padding-top:10px;">未有符合的预约订单</p>

<?php }else{?>
    <table>
        <tr>
            <th style="width:200px;">工单号</th>
            <th style="width:80px;">车牌号</th>
            <th style="width:100px;">服务</th>
            <th style="width:250px;">建单时间</th>
            <th style="width:80px;">工单状态</th>
            <th>操作</th>
        </tr>
        <?php foreach($orders as $order): ?>
            <tr>
                <td><?php echo $order['order_no'];?></td>
                <td><?php echo $order['car']['car_no'];?></td>
                <td><?php echo $order['service_name'];?></td>
                <td><?php echo date("Y-m-d h:m:s",$order['create_time']);?></td>
                <td>
                    <?php
                    switch($order['status']){
                        case 11 : echo '待开工';
                            break;
                        case 20 : echo '工程中';
                            break;
                        case 21 : echo '待审验';
                            break;
                        case 30 : echo '已完工';
                            break;
                        case 40 : echo '已付款';
                            break;
                        case 50 : echo '已评价';
                            break;
                        case 90 : echo '已取消';
                            break;
                        case 10 : echo '待接单';
                            break;
                    }
                    ?>
                </td>
                <td>
                    <?php if($order['status'] == 11){?>
                        <a href="#" ><span onclick="del_order(<?php echo $order['id'];?>);">取消</span></a>
                    <?php }else if($order['status'] == 30){?>
                        <a href="index.php?r=orders/to_payment&order_no=<?php echo $order['order_no'];?>">结账</a>
                    <?php }else if($order['status'] == 10){?>
                        <a href="#"><span onclick="get_order(<?php echo $order['order_no'];?>);" >接单</span></a>
                    <?php }?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php }?>
