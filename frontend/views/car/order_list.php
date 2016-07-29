<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/9
 * Time: 20:01
 */

use yii\widgets\LinkPager;
?>

<div>
    <a title="详细情况" href="index.php?r=car/detail&car_id=<?php echo $car['id']; ?>"><h4><?php echo $car['car_no']; ?></h4></a>
    <a href="index.php?r=car/order_add&car_id=<?php echo $car['id']; ?>">新建工单</a>
</div>
<div>
    <table>
        <tr>
            <th style="width:150px;">订单号</th>
            <th style="width:150px;">创建时间</th>
            <th style="width:100px;">包含服务</th>
            <th style="width:100px;">费用</th>
            <th style="width:300px;">操作</th>
        </tr>
        <?php foreach($orders as $order): ?>
        <tr>
            <td>
                <a href="index.php?r=orders/detail&order_id=<?php echo $order['id']; ?>">
                <?php echo $order['order_no']; ?>
                </a>
            </td>
            <td><?php echo date("Y-m-d",$order['create_time']);?></td>
            <td><?php echo $order['service_name'];?></td>
            <td><?php if(!empty($order['total_price']) && $order['total_price'] != 0){echo "￥".$order['total_price'];}else{ echo "未结算";}?></td>
            <td id="copy_order">
                <a href="index.php?r=car/order_goods_add&id=<?php echo $order['id'];?>">
                    选择材料
                </a>
                &nbsp;&nbsp;
                    <input type="button" value="复制工单" onclick="copy_order(<?php echo $order['id'];?>);" />
            </td>
        </tr>
        <?php endforeach;?>
        <tr>
            <td colspan="4">
                <?= LinkPager::widget(['pagination' => $pages]); ?>
            </td>
        </tr>
    </table>
</div>
<script>
    function copy_order(id){
        alert("请输入当前里程！");
        var htm = "<div>当前里程<input type='text' id='mg' /><br/><input type='button' value='确定' onclick='sure_to_copy("+id+");' class='btn-info' /></div>"
        $("#copy_order").html(htm);
    }
    function sure_to_copy(id){
        var mg = $("#mg").val();
        if(mg == ''){
            alert("请输入当前公里数");
        }else {
            $.ajax({
                type: 'post',
                url: 'index.php?r=car/copy_order',
                data: {'id': id, 'mg' : mg},
                success: function (data) {
                    if (data == 111) {
                        alert("操作成功！");
                        location.href = "index.php?r=orders";
                    } else {
                        alert("系统繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
</script>

