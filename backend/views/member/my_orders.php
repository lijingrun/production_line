<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/12
 * Time: 21:50
 */
use yii\widgets\LinkPager;
?>
<script>
    function check_time(order_no){
        $.ajax({
            type : 'post',
            url : 'index.php?r=member/check_time',
            data : {'order_no' : order_no},
            success : function(data){
                alert(data);
            }
        });
    }
    function body_check(id){
        $("#order_body"+id).show();
        $("#order_title"+id).removeAttr("onclick");
        $("#order_title"+id).attr("onclick","hide_body("+id+");");
    }
    function hide_body(id){
        $("#order_body"+id).hide();
        $("#order_title"+id).removeAttr("onclick");
        $("#order_title"+id).attr("onclick","body_check("+id+");");
    }
</script>
<div>
<div style="padding:20px;">
<!--    <a href="index.php?r=member/order_add">-->
<!--        <span style="font-size: 20px;">新建工单</span>-->
<!--    </a>-->
</div>
    <?php if(!empty($resaons)){ ?>
        <a href="index.php?r=member/reason">
            <div class="alert alert-danger" role="alert" style="background-color:#7CFC00;">
                <strong>亲爱的会员：</strong>我们检查到您的爱车需要增加一些保养服务，请点击这里进行确认或者取消！
            </div>
        </a>
    <?php } ?>
    <?php if($back > 0){ ?>
    <div style="padding-bottom: 10px;">
        <a href="index.php?r=member/order_back_list">
            <input type="button" value="返工申请" class="btn-block" />
        </a>
    </div>
    <?php } ?>
    <div >
        <?php foreach($orders as $order): ?>
            <div class="panel panel-success">
                <div class="panel-heading" onclick="body_check(<?php echo $order['id'];?>);" id="order_title<?php echo $order['id']?>" >
                    <h3 class="panel-title"><?php echo $order['service_name']; ?> <span style="float:right;">展开</span></h3>
                </div>
        <div class="panel-body"  style="font-size: 20px;display: none;" id="order_body<?php echo $order['id']?>">
            <a href="index.php?r=member/order&order_id=<?php echo $order['id']?>">
            <div class="alert alert-info" role="alert">
            <p>车辆：<?php echo $order['car']['car_no']; ?></p>
<!--            <p>服务：--><?php //echo $order['service_name']; ?><!--</p>-->
            <p>完成时间：<?php
                if(!empty($order['finish_time'])){
                    echo date("Y-m-d", $order['finish_time']);
                }else{
                   echo "";
                };
                ?>
            </p>
            <p>保养里程：<?php echo $order['mileage'];?>km</p>
            <p>工单状态：
            <?php
                switch($order['status']){
                    case 11 : echo "待开工";
                              if(!empty($order['store_id'])){
                                  echo "<br /><a href='#' onclick='check_time(".$order['order_no'].");'><span><input type='button' value='查看排队时间' class='btn-success'></span></a>";
                              }
                        break;
                    case 12 : echo '检测中';
                        break;
                    case 20 : echo '施工中';
                        if(empty($order['finish_time'])){
                            $time = ceil((time() - $order['begin_time'])/60);
                        }else{
                            $time = ceil(($order['finish_time'] - $order['begin_time'])/60);
                        }
                              echo "(".$time."分钟)";
                        break;
                    case 21 : echo '待审验';
                        break;
                    case 30 : echo '已完工';
                        break;
                    case 40 : echo '已付款';
                        break;
                    case 50 : echo "<a href='index.php?r=member/evaluate_detail&order_id=".$order['id']."''>已评价</a>";
                        break;
                    case 90 : echo '已取消';
                        break;
                    case 10 : echo '待接单';
                              if(!empty($order['store_id'])){
                                  echo "(已预约)";
                                  echo "<br /><a href='#' onclick='check_time(".$order['order_no'].");'><span><input type='button' value='查看排队时间' class='btn-success'></span></a>";
                              }
                        break;
                }
            ?>
            </p>
            </div>
            </a>
            <p>
                <?php if($order['status'] == 40){?>
                    <a href="index.php?r=member/evaluate&order_id=<?php echo $order['id'];?>">
                    <input type="button" value="评价" />
                    </a>
                <?php }else if($order['status'] == 20){ ?>
                    <a href="index.php?r=carema/check&order_id=<?php echo $order['id'];?>">
                        <input type="button" value="查看现场" class="btn-danger" />
                    </a>
                <?php } ?>
            </p>
        </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div align="center">
        <?= LinkPager::widget(['pagination' => $pages]); ?>
    </div>
</div>