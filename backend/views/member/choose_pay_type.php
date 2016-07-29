<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/8
 * Time: 14:13
 */
?>
<div>
    <div style="padding-top:20px;font-size: 20px;padding-left: 50px;" >
        <p>
        请选择线上付款方式：
        </p>
        <form method="post">
            <div style="margin-top: 20px;" >
                <a href="/backend/weixin_pay/example/jsapi.php">
                    <input type="radio" name="pay_type" value="1" checked />微信支付
                </a>
            </div>
            <div style="margin-top: 20px;" id="isplist" >
                <input type="radio" name="pay_type" value="2" />支付宝支付
            </div>
            <input type="hidden" value="<?php echo $order_id?>" name="order_id" />
        </form>
    </div>
</div>
