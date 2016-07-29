<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/5
 * Time: 9:53
 */
?>
<script>
    function create_orders(id){
        if(confirm("是否生成该套餐对应的工单？")){
            var car_id = $("#car_id").val();
            $.ajax({
                type : 'post',
                url : 'index.php?r=member/create_package_order',
                data : {'p_id' : id, 'car_id' : car_id},
                success : function(data){
                    if(data == 111){
                        alert("成功！");
                        location.href='index.php?r=member/my_order'
                    }else{
                        alert("失败");
                    }
                }
            });
        }
    }
</script>
<div>
    <?php if(!empty($packages)){ ?>
    <?php foreach($packages as $package): ?>
    <div class="panel panel-success">
        <div  class="panel-heading" >
            <h3 class="panel-title">
                <?php echo $pack['name']; ?>----<?php echo $package['nums']?>次
            </h3>
        </div>
        <div class="panel-body">
            <?php foreach($package['goods'] as $goods): ?>
            <p><?php echo $goods['goods_name']."--".$goods['nums'];?></p>
            <?php endforeach; ?>
        </div>
        <p>
            对应车辆：
            <select id="car_id">
                <?php foreach($my_cars as $car): ?>
                <option value="<?php echo $car['id']?>"><?php echo $car['car_no']?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <input type="button" value="生成套餐工单" onclick="create_orders(<?php echo $pack['id']?>);" />
        </p>
    </div>
    <?php endforeach; ?>
    <? }else{ ?>
        <div>您还没有任何可用套餐卷，可以到门店去进行购买！</div>
    <?php } ?>

</div>
