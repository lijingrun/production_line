<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/4
 * Time: 9:13
 */
?>
<div>
    <div>
        <a href="index.php?r=package/add">
            <input type="button" value="添加套餐" class="btn-success" />
        </a>
        <a href="index.php?r=goods/coupon_list">
            <input type="button" value="优惠卷" class="btn-success" />
        </a>
    </div>
    <div style="padding-top:20px;">
        <table border="1">
            <tr>
                <th style="width:150px;">套餐名</th>
                <th style="width:50px;">套餐价钱</th>
                <th style="width:250px;">包含商品</th>
                <th style="width:50px;">激活</th>
                <th style="width:250px;">操作</th>
            </tr>
            <?php foreach($packages as $package): ?>
            <tr>
                <td><?php echo $package['name'];?></td>
                <td><?php echo $package['price'];?></td>
                <td>
                    <?php
                        foreach($package['goods'] as $good):
                            echo "<p>".$good['goods_name']."</p>";
                        endforeach;
                    ?>
                </td>
                <td><?php echo $package['status'] == 1 ? '已激活' : '未激活';?></td>
                <td>
                    <a href="index.php?r=package/add&id=<?php echo $package['id']?>">修改</a>
                    <a href="index.php?r=package/goods_list&package_id=<?php echo $package['id']?>">修改产品</a>
                    <a href="index.php?r=package/to_member&package_id=<?php echo $package['id']?>">客户购买</a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>
    </div>
</div>
