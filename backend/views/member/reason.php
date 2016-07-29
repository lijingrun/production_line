<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/19
 * Time: 10:36
 */
?>
<script>
    function give_up(id){
        if(confirm("是否确定放弃该次加单？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=member/give_up_reason',
                data : {'id' : id},
                success : function(data){
                    if(data == 111){
                        alert('操作成功！');
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
    function sure_to_add(id){
        if(confirm("确定后我会回为您自动新增一张对应工单，是否确定？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=member/sure_to_add',
                data : {'id' : id},
                success : function(data){
                    if(data == 111){
                        alert("操作成功！");
                        location.href='index.php?r=member/my_order';
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
</script>
<div>
    <?php if(!empty($reasons)){?>
    <?php foreach($reasons as $reason): ?>
    <div class="panel panel-default" style="margin:10px;">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $reason['service_name'];?></h3>
        </div>

        <div class="alert alert-warning" role="alert">
            <p>检测时间：<?php echo date("Y-m-d H:i:s",$reason['create_time']);?></p>
            <p>
                需要增加的服务：<?php echo $reason['service_name'];?>
            </p>
            <strong>
                增加原因：<?php echo $reason['reason'];?>!
            </strong>
            <?php if(!empty($reason['reason_goods'])){ ?>
            <h5>包含商品：</h5>
            <?php foreach($reason['reason_goods'] as $goods): ?>
            <p>
                <?php echo $goods['goods_name'].$goods['style']."(￥".$goods['price'].")";  ?>
            </p>
            <?php endforeach; ?>
                <?php } ?>
        </div>
        <p style="padding-left: 20px;">
            <input type="button" value="确定加单" class="btn-success" onclick="sure_to_add(<?php echo $reason['id']?>);"/>
            <input class="btn-danger" type="button" value="我不需要加单" onclick="give_up(<?php echo $reason['id']?>);" />
        </p>
    </div>
    <?php endforeach;?>
        <span style="color:red;">注：这个只是一个加服务提醒，需要您手动进行加单，如果我们保养完您爱车之后仍然收不到您的加单，我们将视为您不需要加单</span>
    <?php }?>
</div>
