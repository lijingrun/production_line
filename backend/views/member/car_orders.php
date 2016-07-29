<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/13
 * Time: 9:41
 */
use yii\widgets\LinkPager;
?>

<div style="padding-top:20px;;">
    <?php if(!empty($orders)){ ?>
        <a href="index.php?r=member/add_order&car_id=<?php echo $car['id'];?>">
            <span style="font-size: 20px;" class="label label-success">马上下单进行保养！</span>
        </a>
        <?php if(!empty($reason)){?>
            <div style="padding-top:20px;font-size: 20px;">
            <a href="index.php?r=member/reason&car_id=<?php echo $car['id'];?>">
                <span style="color:red">我们检查到您的车需要进行保养，请点击查看</span>
            </a>
            </div>
        <?php }?>
    <?php foreach($orders as $order): ?>
            <a href="index.php?r=member/order&order_id=<?php echo $order['id'];?>">
    <div style="font-size: 20px;margin-top: 20px;" class="alert alert-danger" role="alert">
        <p>
            完成保养时间：
            <?php if(!empty($order['finish_time'])){?>
            <?php echo date('Y-m-d',$order['finish_time']);?>
            <?php }else{?>
            还未完成保养
            <?php }?>
        </p>
        <p>
            保养里程：
            <?php if(!empty($order['mileage'])){?>
            <?php echo $order['mileage']; ?>
            <?php }else{?>
                还未开始保养
            <?php }?>
        </p>
        <p>保养内容：<?php echo $order['service_name']; ?></p>

    </div>
            </a>

    <?php endforeach; ?>
        <div>
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    <?php }else{ ?>
    <h3>您的该车暂时没有保养信息！</h3>
        <a href="index.php?r=member/add_order&car_id=<?php echo $car['id'];?>">
            <span style="font-size: 20px;">马上下单进行保养！</span>
        </a>
    <?php }?>
</div>
