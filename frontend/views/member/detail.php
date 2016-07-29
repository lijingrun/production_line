<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/5
 * Time: 14:12
 */
?>

<div>
    <h4><?php echo $member['user_name']?></h4>
    <h4>电话：<?php echo $member['phone'];?></h4>
    <h4>消费积分：<?php echo $total_cons_point?></h4>
    <h4><a href="index.php?r=member/coupon&member_id=<?php echo $member['id']?>"><span class="label label-success">卡卷</span></a></h4>
    <h4>
        <a href="index.php?r=member/package&member_id=<?php echo $member['id']?>">
            <span class="label label-info">套餐</span>
        </a>
    </h4>
	<div  >
	<h4>车辆列表</h4>
	<ul>
	<?php foreach($cars as $car): ?>
	<li>
		<a href='index.php?r=car/orders&car_id=<?php echo $car['id'];?>'>
		<?php echo $car['car_no']; ?>
		</a>
	</li>
	<?php endforeach; ?>
	</ul>
	</div>
    <div>
        <h4>剩余积分明细</h4>
    <table>
        <tr>
            <th style="width:150px;">积分订单号</th>
            <th style="width:80px;">订单积分</th>
            <th style="width:80px;">剩余积分</th>
            <th style="width:150px;">获取时间</th>
            <th style="width:150px;">有效期</th>
        </tr>
        <?php foreach($member_cons_point as $point): ?>
        <tr>
            <th>
                <a href="index.php?r=orders/detail&order_id=<?php echo $point['order']['id'];?>">
                <?php echo $point['order_no'];?>
                </a>
            </th>
            <th><?php echo $point['total_point'];?></th>
            <th><?php echo $point['surplus'];?></th>
            <th><?php echo date('Y-m-d',$point['created_time']);?></th>
            <th><?php echo date('Y-m-d',$point['ev_time']);?></th>
        </tr>
        <?php endforeach;?>
    </table>
    </div>
    <div>
        <h4>积分消费明细</h4>
        <table>
            <tr>
                <th style="width:100px;">消费订单号</th>
                <th style="width:80px;">消费积分</th>
                <th style="width:180px;;">消费时间</th>
            </tr>
            <?php foreach($point_log as $log): ?>
            <tr>
                <th><?php echo $log['order_no']; ?></th>
                <th><?php echo $log['point']; ?></th>
                <th><?php echo date("Y-m-d",$log['create_time']); ?></th>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
