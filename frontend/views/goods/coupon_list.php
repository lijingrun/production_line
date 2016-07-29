<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/4/10
 * Time: 11:36
 */
?>

<div>
    <table border="1">
        <tr>
            <th style="width:200px;">优惠卷名称</th>
            <th style="width:200px;">对应产品</th>
            <th style="width:80px;">优惠金额</th>
            <th style="width:80px;">使用条件</th>
            <th style="width:80px;">期限</th>
            <th style="width:180px;">操作</th>
        </tr>
        <?php foreach($coupons as $coupon): ?>
        <tr>
            <td><?php echo $coupon['coupon_name'];?></td>
            <td><?php echo $coupon['goods_id']['goods_name'];?></td>
            <td><?php echo '￥'.$coupon['price'].'元';?></td>
            <td><?php echo '￥'.$coupon['min_price'].'元'?></td>
            <td><?php echo $coupon['validity_period'].'天';?></td>
            <td>
                <a href="index.php?r=goods/grant_coupon&coupon_id=<?php echo $coupon['coupon_id']?>">
                    发放
                </a>
                <a href="index.php?r=goods/edit_coupon&coupon_id=<?php echo $coupon['coupon_id']?>">
                    修改
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <div style="font-size: 10px;padding-top:20px;padding-bottom: 20px;">
        <form method="post">
            <p>优惠卷名：<input type="text" name="coupon_name"></p>
            <p>
                使用说明：
                <textarea name="explain" cols="20" rows="5"></textarea>
            </p>
            <p>
                到期期限：<input type="text" name="validity_period" />天
            </p>
            <p>
                抵扣金额：<input type="text" name="price" />
            </p>
            <p>
                使用条件：<input type="text" name="min_price" /><span>订单总价多少能用</span>
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
</div>
