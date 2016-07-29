<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/11
 * Time: 22:42
 */
?>
<script>
    function settlement(){
        var total_price = <?php echo $total_price; ?>;
        var discount_type = $("#discount_type").val();
        var realy_price = 0;
        var worker_price = <?php echo $worker_price; ?>
//        var balance = <?php //echo $member['balance'];?>//;

        var advance = <?php echo $member['balance']; ?>;
        if(discount_type == 'no_discount'){  //不使用优惠
            $("#settlement").html('');
            var realy_price = total_price;
        }else if(discount_type == 'discount'){ //会员优惠
            var discount = <?php echo $discount['discount']; ?>;
            var realy_price = (total_price-worker_price)*discount+worker_price;

        }else if(discount_type == 'cons_point'){ //消费积分
            var point = $("#cons_point").val();
            var t_point = <?php echo $can_cons_point;?>;
            if(point > 100){
                alert("一次最多只能使用100积分");
            }else if(point > t_point){
                alert("客户积分不足");
            }else if(point == 0){
                alert("请输入抵扣积分");
            }else{
                var realy_price = total_price-point;
            }
        }else if(discount_type == 'coupon'){   //代金卷
            var coupon_id = $("#coupon_id").val();
            $.ajax({
                type : 'post',
                async: false,
                url : 'index.php?r=orders/get_coupon_price',
                data : {'coupon_id' : coupon_id},
                success : function(data){
                    realy_price = total_price-data;
                }
            });
        }else if(discount_type == 'balance'){  //余额抵扣
            if(advance >=  total_price){ //全部余额抵扣
                realy_price = 0;
            }else{ //抵扣一部分
                realy_price = total_price - advance;
            }
        }
            realy_price = realy_price.toFixed(2);
            var html = "<p>应收：￥" + realy_price + "</p><p>整单折让金额：<input type='text' id='m_discount'></p>";
            var pay_html = "<input type='button' value='计算应收并确认收款' onclick='has_paid(" + realy_price + ");' />";
            var pay_type_html = "<p><select id='pay_type'>";
//            if(advance >= realy_price){
//                pay_type_html += "<option value='advance'>余额支付</option>";
//            }
            pay_type_html += "<option value='cash'>现金支付</option>";
            pay_type_html += "<option value='card'>刷卡支付</option>";
            pay_type_html += "<option value='weixin'>微信支付</option>";
            pay_type_html += "</select></p>";
            $("#settlement").html(html);
            $("#pay").html(pay_type_html);
            $("#pay").append(pay_html);
    }
    //选择优惠方式
    function choose_discount_type(){
        var type = $("#discount_type").val();
        $("#pay").html('');
        if(type == 'no_discount'){  //不选择优惠
            $("#discount_input").html('');
            $("#settlement").html("<input type='button' value='结算' onclick='settlement();' />");
        }else if(type == 'discount'){  //会员等级
            $("#discount_input").html('');
            $("#settlement").html("<input type='button' value='结算' onclick='settlement();' />");
        }else if(type == 'cons_point'){  //积分抵现
            $("#discount_input").html('');
            var htm = "<input type='text' id='cons_point' value='0' /><span style='font-size:12px;color:red;'>*1积分抵扣1元，每次最多可以使用100积分*</span>";
            $("#settlement").html("<input type='button' value='结算' onclick='settlement();' />");
            $("#discount_input").html(htm);
        }else if(type == 'coupon'){  //现金卷
            $("#discount_input").html('');
            $("#settlement").html("<input type='button' value='结算' onclick='settlement();' />");
            var order_no = <?php echo $order['order_no'];?>;
            var total_price = <?php echo $total_price; ?>;
            $.ajax({
                type : 'post',
                url : 'index.php?r=orders/get_coupon_by_order_no',
                data : {'order_no' : order_no, 'total_price' : total_price},
                success : function(data){
                    $("#discount_input").html(data);
                }
            });
        }else if(type == 'balance'){ //余额支付
            $("#discount_input").html('');
            $("#settlement").html("<input type='button' value='结算' onclick='settlement();' />");
        }
    }
    function has_paid(realy_price){
        var m_discount = $("#m_discount").val(); //整单折让金额
        realy_price -= m_discount;
        if(confirm("应收款￥"+realy_price+"；是否确定已收并选择了对应的支付方式？")){
            var member_id = <?php echo $member['id'] ?>;
            var discount_type = $("#discount_type").val();//折扣方式
            var total_price = <?php echo $total_price; ?>; //订单总额
            var pay_type = $("#pay_type").val();
            var order_no = <?php echo $order['order_no']; ?>;
            if(discount_type == 'coupon'){
                var coupon_id = $("#coupon_id").val();
            }else if(discount_type == 'cons_point'){
                var cons_point = $("#cons_point").val();
            }
            $.ajax({
                type : 'post',
                url : 'index.php?r=orders/pay_order',
                data : {'cons_point' : cons_point, 'member_id' : member_id, 'order_no' : order_no, 'coupon_id' : coupon_id,'realy_price' : realy_price, 'm_discount' : m_discount, 'discount_type' : discount_type, 'total_price' : total_price, 'pay_type' : pay_type},
                success : function(data){
                    if(data == 111){
                        alert("结算成功！");
                        location.href="index.php?r=orders";
                    }else{
                        alert(data);
//                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
</script>
<div>
    <h4>订单号：<?php echo $order['order_no']; ?></h4>
    <h4>车牌号：<?php echo $car['car_no'];?></h4>
    <h4>服务：</h4>
    <ul>
        <?php foreach($orders as $val):?>
        <li>
            <?php echo $val['service_name']."(工时费￥".$val['price'].")";?>
        </li>
        <?php endforeach;?>
    </ul>
</div>
<div>
    <h4>价钱：</h4>
    <?php if(count($packages) >0){ ?>
        套餐
        <ul>
            <?php foreach($packages as $package): ?>
            <li>
                <?php echo $package['name']."--￥".$package['price']."(不含工时费)"; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php } ?>
    <table border="1">
        <tr>
            <th style="width:280px;">产品</th>
            <th style="width:80px;">价格</th>
            <th>数量</th>
        </tr>
        <?php foreach($goods as $good):?>
        <tr>
            <td><?php echo $good['goods_name']; if($good['package_id'] != 0){echo "<span style='color:red;'>(套餐产品)</span>";}?></td>
            <td>￥<?php echo $good['price'];?></td>
            <td><?php echo $good['nums'];?></td>
        </tr>
        <?php endforeach;?>
        <tr>
            <td>工费</td>
            <td colspan="2"><?php echo "￥".$worker_price;?><span style="color:red;padding-left:15px;font-size: 10px;">(*工费不打折*)</span></td>
        </tr>
        <tr>
            <td>总价</td>
            <td colspan="2">￥<?php echo $total_price;?></td>
        </tr>
    </table>
    <div style="padding-top:20px;">
        折扣方式：
        <select id="discount_type" onchange="choose_discount_type();">
            <option value="no_discount">不选择折扣</option>
            <?php foreach($has_discount as $key=>$val): ?>
            <option value="<?php echo $key;?>"><?php echo $val['name']."(".$val['value'].")";?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div id="discount_input" style="padding:10px;"></div>
    <div id="settlement" style="padding-top:20px;">
        <input type="button" value="结算" onclick="settlement();" />
    </div>
    <div id="pay">

    </div>
</div>
