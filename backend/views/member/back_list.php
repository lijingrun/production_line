<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/7
 * Time: 13:50
 */
?>

<div>
    <?php foreach($backs as $back): ?>
    <div style="padding:10px;margin: 10px;font-size: 18px;color:white;" class="div_back_color">
        <p>原工单：<?php echo $back['order_no'];?></p>
        <p>申请时间：<?php echo date("Y-m-d",$back['created_time']);?></p>
        <p>申请原因：<?php echo $back['why'];?></p>
        <?php if(!empty($back['back_order'])){ ?>
        <p><a href="index.php?r=member/order&order_id=<?php echo $back['back_order'];?>">我们已跟进，具体情况请查看工单 <?php echo $back['back_order'];?></a></p>
        <?php }else{ ?>
        <p>申请已经受理，请尽快到我店进行处理</p>
        <?php } ?>
    </div>
    <?php endforeach; ?>
</div>
