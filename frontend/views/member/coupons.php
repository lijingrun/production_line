<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/4/12
 * Time: 9:00
 */
?>

<div>
    <?php if(empty($member_coupons)){ ?>
    <h4>该会员还未有任何优惠卷</h4>
    <?php }else{ ?>
    <table border="1" style="width:80%;">
        <tr>
            <th>卡卷名</th>
            <th>卡卷号</th>
            <th>对应金额</th>
<!--            <th>对应商品</th>-->
            <th>卡卷状态</th>
            <th>使用日期</th>
            <th>到期时间</th>
            <th>对应订单</th>
        </tr>
        <?php foreach($member_coupons as $coupon): ?>
        <tr>
            <td><?php echo $coupon['coupon_id']['coupon_name']?></td>
            <td><?php echo $coupon['coupon_sn']?></td>
            <td><?php echo $coupon['coupon_id']['price']?></td>
            <td>
                <?php
                    switch($coupon['status']){
                        case 1 : echo '已用';
                            break;
                        case 2 : echo "可用";
                            break;
                        case 0 : echo "不可用";
                            break;
                    }
                ?>
            </td>
            <td><?php echo empty($coupon['use_time']) ? '未用' : date("Y-m-d",$coupon['use_time']);?></td>
            <td><?php echo date("Y-m-d",$coupon['end_time'])?></td>
            <td><?php echo empty($coupon['use_order']) ? '未用' : $coupon['use_order'];?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php } ?>
</div>
