<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/7
 * Time: 20:38
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
?>
<style>
    td{
        padding:5px;
    }
</style>
<script>
    function find_member(){
        var phone = $("#phone").val().trim();
		var car_no = $("#car_no").val().trim();
        location.href="index.php?r=member&phone="+phone+"&car_no="+car_no;
    }
    function change_type(member_id){
        var member_type = $("#member"+member_id).val();
        $.ajax({
            type : 'post',
            url : 'index.php?r=member/change_type',
            data : {'type' : member_type, 'member_id' : member_id},
            success : function(data){
                if(data == 111){
                    alert("修改完成！");
                    location.reload();
                }else{
                    alert("服务器繁忙，请稍后重试！");
                }
            }
        });
    }
</script>
<div>
<!--    <a href="index.php?r=member/add">-->
<!--        <input type="button" value="添加新会员" />-->
<!--    </a>-->
    <a href="index.php?r=member/discount">
        <input type="button" value="会员类型管理" />
    </a>
</div>
<div style="padding:20px;">
    <div>
        车牌号码：<input type="text" value="<?php echo $car_no;?>" id='car_no'/>
        电话号码：<input type="text" id="phone" value="<?php echo $conditions['phone']; ?>" />
        <input type="button" value="查找" onclick="find_member();"/>
    </div>
    <table>
        <tr>
            <th style="width:100px;">客户</th>
            <th style="width:150px;">联系电话</th>
            <th style="width:150px;">会员等级</th>
			<th style='width:120px;'>注册车辆</th>
<!--            <th style="width:100px;">推荐码</th>-->
            <th style="width:150px;">注册时间</th>
            <th style="width:150px;">操作</th>
            <th style="width:150px;">余额</th>
        </tr>
        <?php foreach($members as $member): ?>
        <tr>
            <td>
                <a href="index.php?r=member/detail&member_id=<?php echo $member['id'];?>">
                <?php echo $member['user_name']; ?>
                </a>
            </td>
            <td>
                <?php echo $member['phone']; ?>
            </td>
            <td>
                <select onchange="change_type(<?php echo $member['id']?>);" id="member<?php echo $member['id']?>">
                <?php foreach($member_types as $type): ?>
                    <option value="<?php echo $type['member_type']?>" <?php if($type['member_type'] == $member['type']){echo 'selected';}?>><?php echo $type['type_name'];?></option>
                <?php endforeach;?>
                </select>
            </td>
			<td>
				<?php if(!empty($member['rec_numbers'])){ ?>
				<?php foreach($member['rec_numbers'] as $car): ?>
				<p><a href='index.php?r=car/orders&car_id=<?php echo $car['id']?>'><?php echo $car['car_no']?></a></p>
				<?php endforeach; ?>
				<?php }else{ ?>
				<p>无登记车辆信息</p>
				<?php }?>
			</td>
<!--            <td>--><?php //echo $member['rec_numbers']; ?><!--</td>-->
            <td><?php echo date("Y-m-d",$member['create_time']); ?></td>
            <td>
                <a href="index.php?r=member/cars&id=<?php echo $member['id']; ?>">
                    登记汽车
				</a>
				|
                <a href="index.php?r=member/deposit&id=<?php echo $member['id']; ?>">
                    充值
				</a>
                |
                <a href="index.php?r=member/send_message&id=<?php echo $member['id'];?>">
                    发送微信模板
                </a>
			</td>
			<td align='right'>
				￥<?php echo $member['balance']; ?>
				|
                <a href="index.php?r=member/deposit_details&id=<?php echo $member['id']; ?>">
                    明细
				</a>				
			</td>
        </tr>
		<tr>
			<td cols='7'>
				<div style='width:1200%;border-style:solid; border-width:1px; border-color:#D3D3D3' ></div>
			</td>
		</tr>
        <?php endforeach;  ?>
<!--        <tr>-->
<!--            <td clospan="6">-->
<!--                --><?//= LinkPager::widget(['pagination' => $pages]); ?>
<!--            </td>-->
<!--        </tr>-->
    </table>
    <div>
    <?= LinkPager::widget(['pagination' => $pages]); ?>
    </div>
</div>
