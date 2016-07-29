<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/14
 * Time: 21:08
 */
?>
<script>
    function del_order(id){
        if(confirm("你是否确定取消该订单？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=member/del_order',
                data : {'id' : id},
                success :function(data){
                    if(data == 111){
                        alert("操作成功！");
                        location.href='index.php?r=member/my_order';
                    }else{
                        alert("服务器繁忙，请稍后重试!");
                    }
                }
            });
        }
    }
    function back_to_worke(){
        $("#back_to_work").hide();
        $("#back_sure").show();
    }
    function back_sure(){
        var why = $("#why").val().trim();
        var order_no = <?php echo $order['order_no']?>;
        if(why != ''){
            $.ajax({
                type : 'post',
                url : 'index.php?r=member/back_work',
                data : {'order_no' : order_no, 'why' : why},
                success : function(data){
                    if(data == 111){
                        alert("申请成功，麻烦你将车辆开到本店，我们马上跟你处理！");
                        location.href='index.php?r=member/order_back_list';
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
    function copy_order(id){
        alert("请输入当前里程！");
        var htm = "<div>当前里程<input type='text' id='mg' /><br/><input type='button' value='确定' onclick='sure_to_copy("+id+");' class='btn-info' /></div>"
        $("#copy_order").html(htm);
    }
    function sure_to_copy(id){
        var mg = $("#mg").val();
        if(mg == ''){
            alert("请输入当前公里数");
        }else {
            $.ajax({
                type: 'post',
                url: 'index.php?r=member/copy_order',
                data: {'id': id, 'mg' : mg},
                success: function (data) {
                        alert("操作成功！");
                        location.href = "index.php?r=member/order&order_id="+data;
                }
            });
        }
    }
</script>
<div style="padding:10px;font-size: 20px;">
    <div >

<!--        <p>车牌号：--><?php //echo $order['car']['car_no'];?><!--</p>-->
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $order['car']['car_no'];?></h3>
            </div>
            <div class="panel-body">
                <p><?php echo $order['service_name'];?></p>
<!--                --><?php //foreach($other_orders as $other_order):?>
<!--                    <p>-->
<!--                        --><?php //echo $other_order['service_name']?>
<!--                    </p>-->
<!--                --><?php //endforeach; ?>
                <p>创建时间：<?php echo date("Y-m-d H:i:s", $order['create_time'])?></p>
                <p>工时费：￥<?php echo empty($order['price']) ? '0.00' : $order['price'];?></p>
                <p>
                    <?php
                    switch($order['status']){
                        case 10 : echo '未受理';
                            break;
                        case 11 : echo '未开工';
                            break;
                        case 20 : echo '已开工';
                            break;
                        case 30 : echo '已完工';
                            break;
                        case 40 : echo '已付款';
                            break;
                        case 50 : echo '已评价';
                            break;
                        case 90 : echo '已取消';
                            break;
                    }
                    ?>
                </p>
                <?php if($order['status'] == 10){ ?>
                    <?php if(!empty($order['store_id'])){
                       echo "<p>已预约店铺：".$store['store_name']."</p>";
                    }?>
                <p>
                    <a href="index.php?r=member/choose_store&order_no=<?php echo $order['order_no'];?>">
                        <input type="button" value="选择店铺" class="btn-success" />
                    </a>
                </p>
                <?php } ?>
            </div>
        </div>
<!--        <p>服务内容：--><?php //echo $order['service_name']; ?><!--</p>-->

    </div>
    <div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">包含商品：</h3>
            </div>
            <div class="panel-body">
                <?php foreach($goods as $good):?>
                <p>
                    <?php echo $good['goods_name'].$good['goods_id']['style']."(".$good['goods_id']['spec'].")"."---X".$good['nums']."(单价￥".$good['price'].")";?>
                </p>
                <?php endforeach; ?>
                <?php if($order['status'] < 13  && $order['package_id'] == 0){ ?>
                <div>
                    <a href="index.php?r=member/order_goods_add&order_id=<?php echo $order['id']?>">
                    <input type="button" value="选择/修改商品" class="btn-success" />
                    </a>
                </div>
                <?php  } ?>
            </div>
        </div>

    </div>
    <div>
        <?php
        if($order['total_price'] > 0) {
            echo "总价：￥" . $order['total_price'];
        }
        ?>
    </div>
    <div>
        <?php if($order['status'] == 40){ ?>
            <a href="index.php?r=member/evaluate&order_id=<?php echo $order['id'];?>">
                <input type="button" value="评价" class="btn-info" style="margin-bottom: 10px;" />
            </a>
        <?php } ?>
    </div>
    <?php if($order['status'] >= 30){ ?>
    <div id="copy_order">
        <input type="button" class="btn-success" value="再次进行该服务" onclick="copy_order(<?php echo $order['id'];?>)" />
    </div>
    <?php } ?>
    <div>
        <?php if($order['status'] == 10 || $order['status'] == 11){?>
            <input type="button" value="取消工单" onclick="del_order(<?php echo $order['id']; ?>)" class="btn-danger" />
            <a href="index.php?r=member/add_order&car_id=<?php echo $order['car_id'];?>">
                <input type="button" value="继续下单" class="btn-info" />
            </a>
        <?php }?>
    </div>
    <div>
        <?php if($order['status'] == 20){?>
        <a href="index.php?r=carema/check&order_id=<?php echo $order['id']?>"><input type="button" value="查看现场" class="btn-info" /></a>
        <?php }?>
    </div>
    <div>
        <?php if($order['status'] == 30){?>
        <a href="index.php?r=member/goto_pay&order_id=<?php echo $order['id'];?>"><input type="button" value="线上支付"></a>
        <?php }?>
    </div>
    <div id="back_to_work">
        <?php if(($order['status'] == 40 || $order['status'] == 50) && ($order['create_time'] > (time()-259200))){ ?>
        <input type="button" class="btn-danger" value="要求返工" onclick="back_to_worke();" />
        <?php }?>
    </div>
    <div id="back_sure" style="display: none">
        请填写返工原因：
        <input type="text" id="why" />
        <p>
            <input type="button" value="确认" onclick="back_sure();" class="btn-success" />
        </p>
    </div>
</div>
