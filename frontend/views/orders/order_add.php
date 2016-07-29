<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/9
 * Time: 20:29
 */

?>
<script>
    function input_nums(id){
        var htm = "数量：<input style='width:50px;' type='text' value='1' name='nums[]' />";
        if($("#check_box"+id).is(":checked")){
            $("#input"+id).html(htm);
        }else{
            $("#input"+id).html('');
        }
    }
    function del_goods(id){
        if(confirm("是否确定删除产品？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=orders/del_reason_goods',
                data : {'id' : id},
                success : function(data){
                    if(data == 111){
                        alert("操作成功！");
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
</script>
<form method="post" id="form">
    <div>
        <p>加单原因：<?php echo $reason['reason'];?></p>
        <p>加单服务：<?php echo $reason['service_name'];?></p>
    </div>
    <div>
        <h4>车牌号：<?php echo $car['car_no']; ?></h4>
        <input type="hidden" value="<?php echo $car['id']; ?>" name="car_id"  id="car_id"/>
        里程：<input type="text" name="car_mileage" id="car_mileage" value="<?php echo $order['mileage']; ?>" />km
    </div>
    <div>
        加单服务：<?php echo $reason['service_name'];?>
        <?php if(!empty($r_order)){ ?>
        <span style="color:red;">已经新建了工单，正在等待客户确认！</span>
        <?php } ?>
    </div>
    <div style="padding:10px;">
        已选产品：
        <?php foreach($choose_goods as $choose_good): ?>
            <p>
                <?php echo $choose_good['goods_name'].$choose_good['goods_id']['style'].'---X'.$choose_good['nums'];?>
                <input type="button" class="btn-danger" value="删除" onclick="del_goods(<?php echo $choose_good['id']?>);" />
            </p>
        <?php endforeach; ?>
    </div>
    <?php if(!empty($goods)){ ?>
    <div>
        <?php foreach($goods as $good): ?>
            <div>
                <input type="checkbox" name="goods_ids[]" value="<?php echo $good['goods_id']?>" id="check_box<?php echo $good['goods_id']?>" onclick="input_nums(<?php echo $good['goods_id']?>);" />
                <span><?php echo $good['goods_name'].$good['style']."(￥".$good['price'].")"?></span>
                <p id="input<?php echo $good['goods_id']?>">
                </p>
            </div>
        <?php endforeach; ?>
    </div>
    <?php } ?>
    <div>
        <input type="submit" value="提交订单" onclick="check_data();" class="btn-success" />
    </div>
</form>
