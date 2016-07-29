<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/7
 * Time: 22:03
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<h4>
    <?php echo $member['user_name']; ?>
</h4>

<style>
#deposit_list td, #deposit_list th {
	padding: 3px .5em;
	border: 1px solid gray;
}
#deposit_list th {
	text-align: center;
}
</style>

<div>
    <?php if(!empty($deposits)){?>
	<table id="deposit_list" width="100%">
	<tr>
	<th width="10%">充值计划ID</th>
	<th width="15%" align="center">实际付款金额</th>
	<th width="15%" align="center">充值金额</th>
	<th width="25%" align="center">充值时间</th>
	<th>附加信息</th>
	</tr>
	<?php foreach($deposits as $deposit): ?>
		<tr>
		<td><?php echo $deposit['plan_id']; ?></td>
		<td align="right">￥<?php echo $deposit['cash_amount']; ?></td>
		<td align="right">￥<?php echo $deposit['deposit_amount']; ?></td>
		<td align="center"><?php echo date("Y-m-d H:i:m",$deposit['create_time']); ?></td>
		<td><?php echo $deposit['description']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
	<? }else{?>
    <p style="color:red">该客户没有任何充值记录</p>
    <?php } ?>
</div>
