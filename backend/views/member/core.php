<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/14
 * Time: 10:03
 */
?>

<div style="padding:10px;">
    <div style="font-size: 20px;">
        <div>
            <h3>尊敬的<?php echo $member['user_name']."(".$member['type']['type_name'].")";?></h3>
        </div>
        <div class="panel panel-success">
            <div  class="panel-heading" >
                <h3 class="panel-title">
                    账号情况
                </h3>
            </div>
            <div class="panel-body">
                <p>
                        账户余额：￥<?php echo $member['balance'] ?>
                </p>
                <p><a href="index.php?r=member/cons_point">
                        消费积分：<?php echo $cons_point?>
                    </a>
                </p>
                <p>推荐积分：<?php echo $member['rec_point']?></p>
                <p>
                    <a href="index.php?r=member/my_coupon">
                        我的卡卷包
                    </a>
                </p>
                <p>
                    <a href="index.php?r=member/my_packages">
                        我的套餐
                    </a>
                </p>
<!--        <p>推荐码：--><?php //echo $member['rec_numbers']?><!--</p>-->
            </div>
        </div>
        <a href="index.php?r=member_login/logout">
            <input type="button" value="注销账号" />
        </a>
    </div>

</div>
