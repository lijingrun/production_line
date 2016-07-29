<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/4/11
 * Time: 8:44
 */
?>


<div>
    <div style="padding-top: 20px;">
    <?php if(!empty($coupons)){ foreach($coupons as $coupon): ?>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $coupon['coupon_id']['coupon_name']?></h3>
        </div>
        <div class="panel-body">
            <p>优惠卷号：<?php echo "N0.".$coupon['coupon_sn'];?></p>
            <p>抵扣金额：<?php echo "￥".$coupon['coupon_id']['price'];?></p>
            <p>使用条件：消费<?php echo "￥".$coupon['coupon_id']['min_price'];?>元以上</p>
            <p>有效期到：<?php echo date("Y-m-d",$coupon['end_time'])?></p>
        </div>
    </div>
    <?php endforeach; }else{ ?>
        <p>您暂时还未有任何优惠卷！</p>
    <?php } ?>
    </div>
</div>
