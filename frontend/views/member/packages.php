<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/20
 * Time: 11:56
 */
?>

<div>
    <?php if(empty($packages)){ ?>
    <h4>该会员还未有购买任何套餐</h4>
    <?php }else{ ?>
    <div>
        <?php foreach($packages as $package): ?>
        <div class="panel panel-success" style="width:40%;float: left;margin:20px;">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $package['package']['name']; ?></h3>
            </div>
            <div class="panel-body">
                <p>数量：<?php echo $package['nums']?>套</p>
                <p>面额 ：￥<?php echo $package['package']['price']?>/套</p>
                <div>包含商品：
                    <ul>
                        <?php foreach($package['goods'] as $good): ?>
                        <li>
                            <?php echo $good['goods_name']."---X".$good['nums']; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php } ?>
</div>
