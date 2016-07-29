<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/20
 * Time: 14:58
 */
?>

<div>
    <form method="post">
        <p>优惠卷名：<input type="text" name="coupon_name" value="<?php echo $coupon['coupon_name'];?>"></p>
        <p>
            使用说明：
            <textarea name="explain" cols="20" rows="5" value="<?php echo $coupon['explain'];?>"></textarea>
        </p>
        <p>
            到期期限：<input type="text" name="validity_period" value="<?php echo $coupon['validity_period'];?>" />天
        </p>
        <p>
            抵扣金额：<input type="text" name="price" value="<?php echo $coupon['price'];?>" />
        </p>
        <p>
            使用条件：<input type="text" name="min_price" value="<?php echo $coupon['min_price'];?>" /><span>订单总价多少能用</span>
        </p>
        <p>
            针对产品：
            <select name="goods_id">
                <option value="0">通用</option>
                <?php foreach($goods as $good): ?>
                    <option value="<?php echo $good['goods_id']?>"><?php echo $good['goods_name']?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <input type="submit" value="提交" />
        </p>
    </form>
</div>
