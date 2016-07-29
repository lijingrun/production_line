<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/4/10
 * Time: 11:25
 */
?>

<div>
    <form method="post">
        <p>
        对应商品：<?php echo $goods['goods_name'];?>
            <input type="hidden" value="<?php echo $goods['goods_id']?>" name="goods_id" />
        </p>
        <p>
            代金卷名称：<input type="text" name="coupon_name">
        </p>
        <p>
            代金卷金额：<input type="text" name="price">元
        </p>
        <p>
            详细内容：<textarea cols="50" rows="5" name="explain"></textarea>
        </p>
        <p>
            使用条件：<input type="text" name="min_price" /><span>订单总价多少能用</span>
        </p>
        <p>
            有限期限：<input type="text" name="validity_period"/>天
        </p>
        <p>
            <input type="submit" value="添加" />
        </p>
    </form>
</div>
