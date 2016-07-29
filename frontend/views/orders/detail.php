<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/5
 * Time: 14:38
 */
?>
<script>
    function change_goods(order_no){
        $("#goods_list").html('');
        $.ajax({
            type : 'post',
            url : 'index.php?r=orders/get_goods',
            data : {'order_no' : order_no},
            success : function(data){
                $("#goods_list").append(data);
            }
        });
    }
</script>
<script>
    function del_goods(id){
        if(confirm('是否删除该商品?')){
            $.ajax({
                type : 'post',
                url : 'index.php?r=orders/del_goods',
                data : {'id' : id},
                success : function(data){
                    if(data == 111){
                        alert("删除成功！");
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
    <h3><?php echo $car['car_no']."--".$member['user_name']."(".$member['phone'].")";?></h3>
    <h4>保养里程：<?php echo $order['mileage']?>km</h4>
    <div>
        <?php if(!empty($package)){ ?>
            <div style="color:red;">
                <?php echo $package['name'];?>套餐
            </div>
        <?php } ?>
        包含服务：
        <ul>
        <?php foreach($orders as $order): ?>
        <li>
            <?php
            if(empty($order['price'])){
                $order['price'] = 0;
            }
            echo $order['service_name']."(工时费￥".$order['price']."元)"
            ?>
        </li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div id="goods_list">
        包含产品：
        <ul>
            <?php foreach($order_goods as $goods): ?>
            <li>
                <?php echo $goods['goods']['goods_name'].$goods['goods']['style']."(￥".$goods['price']."/".$goods['goods']['spec'].")----".$goods['nums']?>&nbsp;&nbsp;&nbsp;<?php if($goods['package_id'] != 0){ echo "<span style='font-size: 10px;color:red;'>(套餐产品)</span>"; } ?>
                <?php if($orders['status'] < 30 && $goods['package_id'] == 0 ){ ?>
                <span onclick="del_goods(<?php echo $goods['id']?>);"><a href="#">X</a></span>
                <?php } ?>
            </li>
            <?php endforeach; ?>
            <a href="index.php?r=car/order_goods_add&id=<?php echo $order_id;?>">
            <input type="button" value="选择商品" class="btn-danger" />
            </a>
        </ul>
        <p>
            下单时间： <?php echo date("Y-m-d H:i:s",$order['create_time'])?>
        </p>
    </div>
    <?php if($order['status'] > 30){ ?>
    <div>
        <p>施工工人：<?php echo $worker['username'];?></p>
        <p>验收工人：<?php echo $examine['username'];?></p>
        <p>开始检测时间：<?php echo date("Y-m-d h:i:s",$order['checked_time']);?></p>
        <p>开始维修时间：<?php echo empty($order['begin_time'])? '' : date("Y-m-d H:i:s",$order['begin_time']);?></p>
        <p>施工完成时间：<?php echo empty($order['finish_time'])? '' : date("Y-m-d H:i:s",$order['finish_time']);?></p>
        <p>交车时间：<?php echo empty($order['get_time'])? '' : date("Y-m-d H:i:s",$order['get_time']);?></p>
        <p>施工耗时：<?php echo ceil(($order['finish_time'] - $order['begin_time'])/60)."分钟"; ?></p>
    </div>
    <?php  } ?>
    <div>
        <?php if($order['total_price'] > 0){ ?>
        <p>
            工单总额：￥<?php echo $order['total_price']; ?>
        </p>
        <?php } if($order['realy_price'] > 0){?>
        <p>
            实际收取：￥<?php echo $order['realy_price'];?>
        </p>
        <?php } if($order['discount'] > 0){ ?>
            <p>
                整单折让金额：￥<?php echo $order['discount'];?>
            </p>
        <?php } if(!empty($order['discount_type'])){ ?>
            <p>
                折扣方式：<?php
                switch($order['discount_type']){
                    case 'balance' : echo "余额低现"; break;
                    case 'discount' : echo "会员折扣"; break;
                    case 'cons_point' : echo "积分抵现"; break;
                    case 'coupon' : echo "现金卷"; break;
                }
                ?>
            </p>
            <p>
                折扣金额：￥<?php echo $order['total_price'] - $order['discount'] - $order['realy_price'];?>
            </p>
        <? } if($order['status'] >= 40){ ?>

            <p>收银员：<?php echo $user['username'];?></p>
        <? } ?>
    </div>
</div>
