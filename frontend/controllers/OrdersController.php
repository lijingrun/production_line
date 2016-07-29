<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/9
 * Time: 22:08
 */
namespace frontend\controllers;

use common\models\Additional_goods;
use common\models\Additional_orders;
use common\models\Car_reason;
use common\models\Coupon;
use common\models\Evaluate;
use common\models\Goods_type;
use common\models\Member_cons_point;
use common\models\Member_coupon;
use common\models\Member_discount;
use common\models\Members;
use common\models\Order_back;
use common\models\Orders;
use common\models\Package;
use common\models\Pay_type;
use common\models\Point_log;
use common\models\Service_goods;
use common\models\Service;
use common\models\Order_goods;
use common\models\Cars;
use common\models\Service_price;
use common\models\Service_type;
use common\models\Store;
use common\models\User;
use common\models\Weixin;
use common\models\Weixin_template;
use common\models\Balance_log;
use yii\data\Pagination;
use common\models\Goods;
use Yii;
use yii\web\Controller;

class OrdersController extends Controller{
    public function actionIndex(){
        $order_no = $_GET['order_no'];
        $status = $_GET['status'];
		$begin_date = $_GET['begin_date'];
		$end_date = $_GET['end_date'];
		if(empty($end_date)){
			$end_date = date("Y-m-d",(time()+86400));
		}
        $all_orders = Orders::find()->orWhere(['store_id' => Yii::$app->session['store_id']])->orderBy(['create_time' => SORT_DESC]);
        if(!empty($order_no)){
            $all_orders->andWhere(['order_no' => $order_no]);
        }
        if(!empty($status)){
            $all_orders->andWhere(['status' => $status]);
        }
		if(!empty($begin_date)){
			$begin_time = strtotime($begin_date);
			$all_orders->andWhere("create_time >=".$begin_time);
		}
		$end_time = strtotime($end_date);
		$all_orders->andWhere("create_time < ".$end_time);
        $pages = new Pagination([
            'totalCount' => $all_orders->count(),
            'pageSize' => 50,
        ]);
        $back_count = Order_back::find()->where(['store_id' => Yii::$app->session['store_id']])->andWhere("back_order is null")->andWhere(['status' => 1])->count();
        $orders = $all_orders->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        foreach($orders as $key=>$order):
            $orders[$key]['car'] = Cars::find()->where(['id' => $order['car_id']])->asArray()->one();
			$goods = Order_goods::find()->where(['order_id' => $order['id']])->asArray()->all();
			$goods_price = 0;
			foreach($goods as $good):
				$goods_price += ($good['price']*$good['nums']);
			endforeach;
			$orders[$key]['goods_price'] = $goods_price;
            $orders[$key]['worker'] = User::find()->where(['id' => $order['worker_id']])->asArray()->one();
        endforeach;
        return $this->render('orders_list',[
            'pages' => $pages,
            'orders' => $orders,
            'order_status' => $status,
            'order_no' => $order_no,
            'back_count' => $back_count,
			'begin_date' => $begin_date,
			'end_date' => $end_date,
        ]);
    }

    //查看评价
    public function actionEvaluate(){
        $id = $_GET['id'];
        $orders = Orders::find()->where(['order_no' => $id])->asArray()->all();
        $ids = array();
        foreach($orders as $order):
            $ids[] = $order['id'];
        endforeach;
        $ids = implode(',',$ids);
        $evas = Evaluate::find()->where("order_id in (".$ids.")")->asArray()->one();
        return $this->render('evaluate',[
            'evas' => $evas,
        ]);
    }

    //客户返工申请
    public function actionOrder_back(){
        //查本店铺的所有返工申请
        $car_no = $_GET['car_no'];
        if(!empty($car_no)){
            $backs = Order_back::find()->where(['store_id' => Yii::$app->session['store_id']])->andWhere(['car_no' => $car_no])->orderBy('id desc')->asArray()->all();
        }else{
            $backs = Order_back::find()->where(['store_id' => Yii::$app->session['store_id']])->orderBy('id desc')->asArray()->all();
        }
        return $this->render('backs_list',[
            'backs' => $backs,
            'car_no' => $car_no,
        ]);
    }

    //不受理返工申请
    public function actionNo_accept_back(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            $why = $_POST['why'];
            $back = Order_back::find()->where(['id' => $id])->one();
            $back->status = 2;
            $back->user_id = Yii::$app->session['user_id'];
            $back->del_reason = $why;
            if($back->save()){
                echo 111;
            }else{
                echo 222;
            }
            exit;

        }
    }

    //修改加单原因
    public function actionChange_reason(){
        $id = $_POST['id'];
        $new_reason = $_POST['reason'];
        $reason = Car_reason::find()->where(['id' => $id])->one();
        $reason->reason = $new_reason;
        if($reason->save()){
            echo 111;
        }else{
            echo 222;
        }
        exit;
    }

    //结算工单金额
    function actionSettlement(){
        if(Yii::$app->request->post()){
            $order_no = $_POST['order_no'];
            $car_type = $_POST['car_type'];
            $cons_point = $_POST['cons_point'];
            $coupon_sn = $_POST['coupon_sn'];
            $total_price = 0;
            $pay_types = Pay_type::find()->asArray()->all();
            //查找所有的工单
            $orders = Orders::find()->where(['order_no' => $order_no])->asArray()->all();
            $car_id = 0;
            $worker_price = 0; //工时费
            $goods_price = 0; //商品费用
            //求工单的工时费总和
            foreach($orders as $key=>$val):
                $car_id = $val['car_id'];
                $worker_price += $val['price'];
            endforeach;
            //如果有输入优惠卷，就查该优惠卷是否能用
            if(!empty($coupon_sn)){
                $member_coupon = Member_coupon::find()->where(['coupon_sn' => $coupon_sn])->andWhere(['>','end_time',time()])->andWhere(['status' => 2])->asArray()->one();
                $coupon = Coupon::find()->where(['coupon_id' => $member_coupon['coupon_id']])->asArray()->one();
                if(empty($coupon)){
                    echo 333; //优惠卷不存在
                    exit;
                }
                $can_user = 3;
            }
            //求工单包含的所有产品
            $order_goods = Order_goods::find()->where(['order_no' => $order_no])->asArray()->all();
            //加上所有的产品费用
            foreach($order_goods as $goods):
                $goods_price += $goods['price']*$goods['nums'];
                //只有订单里面包含优惠卷对应的商品或者优惠卷是通用性的优惠卷才能用
                if($goods['goods_id'] == $coupon['goods_id'] || $coupon['goods_id'] == 0){
                    $can_user = 1;
                }
            endforeach;
            if($can_user == 3){
                echo 222; //优惠卷不可用
                exit;
            }
            //如果有使用积分，就扣除积分部分
            if( $cons_point > 0){
                $goods_price -= $cons_point;
            }
            //如果优惠卷不为空，就直接减去优惠卷的优惠金额,并且将优惠卷状态处理了
            if(!empty($coupon['price'])){
                $goods_price -= $coupon['price'];
                $member_coupon = Member_coupon::find()->where(['coupon_sn' => $coupon_sn])->one();
                $member_coupon->status = 1;
                $member_coupon->use_time = time();
                $member_coupon->use_order = $order_no;
                $member_coupon->save();
            }
            if($goods_price > 0) {
                //查看客户等级，乘以对应折扣，只用商品价格来打折，工时费不打折
                $car = Cars::find()->where(['id' => $car_id])->asArray()->one();
                $member = Members::find()->where(['id' => $car['member_id']])->asArray()->asArray()->one();
                $discount = Member_discount::find()->where(['member_type' => $member['type']])->asArray()->one();
                $goods_price = $goods_price * $discount['discount'];
            }
            $total_price = $goods_price+$worker_price+$total_price;
			//还要查车辆对应的会员信息，查是否存在余额
            if($discount['discount'] < 1){
                echo "应收款：￥".$total_price. "元<span style='color:red;'>(".$discount['type_name']."-".($discount['discount']*10)."折优惠)</span>";
                //echo "<p>整单折让金额：￥<input type='text' value='0' id='changes' style='width:50px;' >元<input type='button' id='to_c' value='折让' onclick='change_price();' /></p>";
            }else {
                echo "应收款：￥".$total_price."元";
				
            }
			echo "<p>整单折让金额：￥<input type='text' value='0' id='changes' style='width:50px;' >元</p>";
			if($member['balance'] > 0){
				echo "<p>余额支付￥<input type='text' value='0' id='advance' style='width:50px;' id='advance' />元 <span style='color:red;'>(会员账号余额：".$member['balance'].")</span></p>";
				echo "<input type='hidden' value='".$member['balance']."' id='m_advance' />";
			}
			echo "<input type='button' id='to_b' value='计算应收' onclick='to_advance();' />";
            echo "<p>实收款：￥<input type='text' value='".$total_price."' id='realy_pay' readonly='readonly' style='width:50px;' />元</p>";
            echo "<input type='hidden' value='".$total_price."' id='total_price'>";
            echo "收款方式：<select id='pay_type'>";
            foreach($pay_types as $pay):
                echo "<option value='".$pay['id']."'>".$pay['pay_name']."</option>";
            endforeach;
            echo "</select>";
            echo "<div style='padding-top:20px;'><input type='button' value='确认收款' id='sure_pay' onclick='has_paid(".$order_no.");'></div>";
            exit;
        }
    }

    //前台手动修改工时费
    public function actionChange_price(){
        if(Yii::$app->request->post()){
            $price = $_POST['price'];
            $order_id = $_POST['order_id'];
            $order = Orders::find()->where(['id' => $order_id])->one();
            $order->price = $price;
            if($order->save()){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //前台收到钱，改变订单状态
    public function actionPay_order(){
        if(Yii::$app->request->post()){
            $realy_price = $_POST['realy_price']; //实收
            $total_price = $_POST['total_price']; //总价
            $pay_type = $_POST['pay_type']; //支付方式
            $m_discount = $_POST['m_discount']; //整单折让金额
            if(empty($m_discount)){
                $m_discount = 0;
            }
            $discount_type = $_POST['discount_type']; //折扣方式
            $coupon_id = $_POST['coupon_id']; //现金卷id
            $order_no = $_POST['order_no']; //订单号
            $member_id = $_POST['member_id']; //客户id
            $cons_point = $_POST['cons_point']; //消费积分
            $advance_pay = 0;
            $pay_name = '';
            switch($pay_type){
                case 'advance' : $pay_name = '余额支付';
                    break;
                case 'cash' : $pay_name = '现金支付';
                    break;
                case 'card' : $pay_name = '刷卡支付';
                    break;
                case 'weixin' : $pay_name = '微信支付';
                    break;
            }
            //如果使用了积分，就扣除相应的积分
            if($cons_point > 0){
                $cons_point_log = new Point_log();
                $cons_point_log['member_id'] = $member_id;
                $cons_point_log['order_no'] = $order_no;
                $cons_point_log['create_time'] = time();
                $cons_point_log['point'] = $cons_point;
                $cons_point_log->save();
                $member_point = Member_cons_point::find()->where(['member_id' => $member_id])->andWhere('surplus > 0')->andWhere("ev_time >".time())->orderBy('ev_time')->asArray()->all();
                $point_for_calculation = $cons_point; //用来计算，循环账号下面所有的消费积分记录，知道减齐为止
                foreach($member_point as $val):
                    //如果消费大于剩余，直接为0,剩余 大于消费，直接减去消费，跳出循环
                    if($point_for_calculation > 0) {
                        if($val['surplus'] < $point_for_calculation){
                            $point_for_calculation -= $val['surpuls'];
                            $val['surplus'] = 0;
                        }else{
                            $val['surplus'] -= $point_for_calculation;
                            $point_for_calculation = 0;
                        }
                        $member_point_edit = Member_cons_point::find()->where(['id' => $val['id']])->one();
                        $member_point_edit->surplus = $val['surplus'];
                        $member_point_edit->save();
                    }else{
                        break;
                    }
                endforeach;
                print_r($member_point);exit;
            }

            //如果有现金卷，将现金卷用了
            if(!empty($coupon_id)){
                $member_coupon = Member_coupon::find()->where(['id' => $coupon_id])->one();
                $member_coupon->status = 1;
                $member_coupon->use_time = time();
                $member_coupon->use_order = $order_no;
                $member_coupon->save();
            }
            //如果是余额支付，就扣除相应的余额
            if($discount_type == 'balance'){
                $advance_pay = $total_price - $realy_price - $m_discount;    //余额扣除 = 总价 - 实收 - 整单折让
                $member = Members::find()->where(['id' => $member_id])->one();
                $member->balance -= $advance_pay;
                $balance_log = new Balance_log();
                $balance_log['user_id'] = Yii::$app->session['user_id'];
                $balance_log['member_id'] = $member_id;
                $balance_log['balance'] = $advance_pay;
                $balance_log['create_time'] = time();
                $balance_log['order_no'] = $order_no;
                $balance_log->save();
                $member->save();
            }
            /**
             * ，首次消费满500元送50分，第二次开始每次消费满500元送25分。积分有效期为三年。
             * 消费已优惠产品或套餐，又或者是当期短时的优惠活动等，此部分金额不再享受赠送积分
             */
            //查看实际给付金额，按照定义给积分
            $member = Members::find()->where(['id' => $member_id])->asArray()->one();
            if($realy_price >= 500){
                //计算除以500得到的整数乘以系数25，如果是首次，再加25
                $coefficient = floor($realy_price/500);
                $get_point = $coefficient*25;
                if($member['cons_point'] == 0){
                    $the_member = Members::find()->where(['id' => $member_id])->one();
                    $the_member->cons_point = 1;
                    $the_member->save();
                    $get_point += 25;
                }
                $new_member_point = new Member_cons_point();
                $new_member_point['order_no'] = $order_no;
                $new_member_point['member_id'] = $member_id;
                $new_member_point['total_point'] = $get_point;
                $new_member_point['surplus'] = $get_point;
                $new_member_point['created_time'] = time();
                $new_member_point['ev_time'] = time()+94608000;
                $new_member_point->save();
                //发送微信信息
                if(!empty($member['open_id'])){
                    $total_member_points = Member_cons_point::find()->where(['member_id' => $member_id])->andWhere("ev_time > ".time())->andWhere("surplus > 0")->asArray()->all();
                    $total_point = 0;
                    foreach($total_member_points as $val):
                        $total_point += $val['surplus'];
                    endforeach;
                    $data = array(
                        'first' => array('value' => '亲爱的会员，您的积分账户有新的变动，具体内容如下：',),
                        'keyword1' => array('value' => time(),'topcolor'=> '#0F12D'),
                        'keyword2' => array('value' => $get_point,'topcolor'=> '#0F12D'),
                        'keyword3' => array('value' => '完成交易','topcolor'=> '#0F12D'),
                        'keyword4' => array('value' => $total_point+$get_point,'topcolor'=> '#0F12D'),
                        'remark' => array('value' => '您可以到个人中心查看所有的卡卷','topcolor'=> '#0F12D'),
                    );

                    $weixin = Weixin::find()->where(['appid' => $member['weixin_id']])->asArray()->one();  //公众号
                    $template = Weixin_template::find()->where(['appid' => $weixin['appid']])->andWhere(['type_id' => 1])->asArray()->one();  //优惠卷发放模板
                    $appid = $weixin['appid'];
                    $secret = $weixin ['app_secret'];
                    $errcode = $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$weixin['access_token'],$data);
                    //access_token无效的话，重新获取
                    if($errcode != 0){
                        $access_token = $this->get_access_token($appid,$secret);
                        if($access_token != false){
                            $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$access_token,$data);
                        }
                    }
                }
            }

            $orders = Orders::find()->where(['order_no' => $order_no])->andWhere(['status' => 30])->all();
            //总金额 = 优惠金额+整单折让金额+折扣金额
            if(empty($m_discount)){
                $m_discount = 0;
            }
            foreach($orders as $order):
                $order->total_price = $total_price;
                $order->status = 40;
                $order->realy_price = $realy_price;
                $order->pay_type = $pay_type;
                $order->discount = $m_discount;  //整单折让金额
                $order->pay_name = $pay_name;
                $order->payee_id = Yii::$app->session['user_id'];
                $order->advance = $advance_pay;
                $order->discount_type = $discount_type;
                $order->save();
            endforeach;
            echo 111;
            exit;
        }
    }

    //追加工单申请列表
    public function actionAdditional(){
        $reasons = Car_reason::find()->where(['status' => 1])->andWhere(['store_id' => Yii::$app->session['store_id']])->asArray()->all();
        foreach($reasons as $key=>$reason):
            $reasons[$key]['worker'] = User::find()->where(['id' => $reason['worker_id']])->asArray()->one();
        endforeach;
        return $this->render('anson_list',[
            'reasons' => $reasons
        ]);
    }

    //追加工单
    public function actionTo_additional(){
        $reason_id = $_GET['reason_id'];
        $reason = Car_reason::find()->where(['id' => $reason_id])->andWhere(['status' => 1])->asArray()->one();
        $order = Orders::find()->where(['order_no' => $reason['order_no']])->asArray()->one();
        $r_order = Additional_orders::find()->where(['reason_id' => $reason_id])->andWhere(['service_id' => $reason['service_id']])->asArray()->one();
        $car = Cars::find()->where(['id' => $reason['car_id']])->asArray()->one();
        $choose_goods = Additional_goods::find()->where(['reason_id' => $reason['id']])->asArray()->all();
        foreach($choose_goods as $key=>$val):
            $choose_goods[$key]['goods_id'] = Goods::find()->where(['goods_id' => $val['goods_id']])->asArray()->one();
        endforeach;
        //先查有无已经生成的工单
        $add_orders = Additional_orders::find()->where(['reason_id' => $reason['id']])->asArray()->one();
        $service_goods_type = Service_goods::find()->where(['service_id' => $reason['service_id']])->asArray()->all();
        $goods_types_id = array();
        foreach($service_goods_type as $type):
            $goods_types_id[] = $type['goods_type'];
        endforeach;
        if(count($goods_types_id) > 0){
            $goods_types_id = implode(',',$goods_types_id);
            $goods = Goods::find()->where("goods_type in (".$goods_types_id.")")->asArray()->all();
        }
        if(empty($reason)){
            Yii::$app->getSession()->setFlash('error','追加申请已经被确认或者追加申请不存在！');
            return $this->redirect('index.php?r=orders/additional');
        }
        if(Yii::$app->request->post()){
            if(empty($add_orders)){
                $new_add_order = new Additional_orders();
            }else{
                $new_add_order = Additional_orders::find()->where(['reason_id' => $reason['id']])->one();
            }
            $new_add_order->order_no = $reason['order_no'];
            $new_add_order->service_id = $reason['service_id'];
            $new_add_order->service_name = $reason['service_name'];
            $new_add_order->create_time = time();
            $new_add_order->begin_time = $order['begin_time'];
            $new_add_order->checked_time = $order['checked_time'];
            $new_add_order->store_id = $order['store_id'];
            $new_add_order->car_id = $order['car_id'];
            $new_add_order->worker_id = $order['worker_id'];
            $new_add_order->create_id = Yii::$app->session['user_id'];
            $new_add_order->reason = $reason['reason'];
            $new_add_order->take_sp = $order['take_sp'];
            $new_add_order->reason_id = $reason['id'];
            if($new_add_order->save()){
                $goods_ids = $_POST['goods_ids'];
                $goods_nums = $_POST['nums'];
                if(!empty($goods_ids)){
                    foreach($goods_ids as $key=>$goods_id):
                        $the_goods = Goods::find()->where(['goods_id' => $goods_id])->asArray()->one();
                        $new_order_goods = new Additional_goods();
                        $new_order_goods->order_no = $order['order_no'];
                        $new_order_goods->goods_id = $the_goods['goods_id'];
                        $new_order_goods->goods_name = $the_goods['goods_name'];
                        $new_order_goods->price = $the_goods['price'];
                        $new_order_goods->nums = $goods_nums[$key];
                        $new_order_goods->reason_id = $reason['id'];
                        $new_order_goods->save();
                    endforeach;
                }
            }
            //发送微信信息
            $member = Members::find()->where(['id' => $car['member_id']])->asArray()->one();
            //绑定微信，就发通知
            if(!empty($member['open_id'])){
                $data = array(
                    'first' => array('value' => '您有一个加单申请,请登录系统到工单池里面进行加单操作！','topcolor'=> '#0F12D'),
                    'keyword1' => array('value' => $car['car_no'],'topcolor'=> '#0F12D'),
                    'keyword2' => array('value' => $reason['reason'] ,'topcolor'=> '#0F12D'),
                    'remark' => array('value' => '为了我们能及时帮您处理问题，请在3小时内完成加单，否则我们会当作您放弃加单' ,'topcolor'=> '#0F12D'),
                );
                //根据客户所在的公众号appid获取需要的微信信息
                $weixin = Weixin::find()->where(['appid' => $member['weixin_id']])->asArray()->one();  //公众号
                $template = Weixin_template::find()->where(['appid' => $member['weixin_id']])->andWhere(['type_id' => 3])->asArray()->one();
                $appid = $weixin['appid'];
                $secret = $weixin ['app_secret'];
                $errcode = $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$weixin['access_token'],$data);
                //access_token无效的话，重新获取
                if($errcode == 41006 || $errcode == 41001 || $errcode == 42001 || $errcode == 40014){
                    $access_token = $this->get_access_token($appid,$secret);
                    if($access_token != false){
                        $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$access_token,$data);
                    }
                }
            }
            Yii::$app->getSession()->setFlash('success','新建成功，需要等待客户确认');
            return $this->redirect('index.php?r=orders/to_additional&reason_id='.$reason['id']);
        }else{
            return $this->render('order_add',[
                'reason' => $reason,
                'car' => $car,
                'order' => $order,
                'goods' => $goods,
                'r_order' => $r_order,
                'choose_goods' => $choose_goods,
            ]);
        }
    }

    //根据类型获取服务
    public function actionFind_service(){
        if(Yii::$app->request->post()){
            $type_id = $_POST['type_id'];
            $services = Service::find()->where(['type_id' => $type_id])->asArray()->all();
//            echo "<select name='service_id' id='service_id' onchange='get_goods();'>";
            echo "<select name='service_id' id='service_id' onchange='get_goods_ajax();' >";
            echo "<option value=\"0\">请选择服务类型</option>";
            foreach($services as $service):
                echo "<option value='".$service['id']."'>".$service['name']."</option>";
            endforeach;
            echo "</select>";
            exit;
        }
    }

    //根据服务类型获取需要的商品
    public function actionGet_goods_ajax(){
        if(Yii::$app->request->post()){
            $service_id = $_POST['service_id'];
            $car_id = $_POST['car_id'];
            $car = Cars::find()->where(['id' => $car_id])->asArray()->one();
//            $service = Service::find()->where(['id' => $service_id])->asArray()->one();
            //查服务下面需要的商品
            $service_goods = Service_goods::find()->where(['service_id' => $service_id])->asArray()->all();
            //查对应的商品信息，并入数组
            foreach($service_goods as $key=>$service_good):
                //类型下面所有商品
                $service_goods[$key]['goods'] = Goods::find()->where(['style_ids' => 'all'])->orWhere(['like','style_ids' ,",".$car['style_id'].","])->andWhere(['goods_type' => $service_good['goods_type']])->asArray()->all();
                //对应的类型
                $service_goods[$key]['goods_type'] = Goods_type::find()->where(['type_id' => $service_good['goods_type']])->asArray()->one();
            endforeach;
            //查服务所有的工时费
            $service_price = Service_price::find()->where(['service_id' => $service_id])->asArray()->all();
            //查汽车类型，并入工时费数组
            if(!empty($service_price)){
                foreach($service_price as $key=>$price):
                    $service_price[$key]['car_type'] = Car_type::find()->where(['type_id' => $price['car_type']])->asArray()->one();
                endforeach;
            }
            //循环输入需要的数组
            foreach($service_goods as $service_good):
                echo "<div style='padding-top:10px;'>";
                echo $service_good['goods_type']['name'].":";
                echo "<select name='goods_ids[]'>";
                foreach($service_good['goods'] as $good):
                    echo "<option value='".$good['goods_id']."'>".$good['goods_name']."(￥".$good['price'].")"."</option>";
                endforeach;
                echo "<option value='0'>不需要（自带）</option>";
                echo "</select>";
                echo "</div>";
            endforeach;
            echo "工时费<ul>";
            foreach($service_price as $price):
                if(empty($price['car_type']['car_type'])){
                    $price['car_type']['car_type'] = "全部车型";
                }
                echo "<li >".$price['car_type']['car_type']."----￥".$price['price']."/次</li>";
            endforeach;
            echo "</ul>";
            exit;
        }
    }

    //删除加单提醒里面的产品
    public function actionDel_reason_goods(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            if(Additional_goods::deleteAll(['id' => $id])){
               echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //订单的详细内容
    public function actionDetail(){
        $order_id = $_GET['order_id'];
        $order = Orders::find()->where(['id' => $order_id])->asArray()->one();
        $orders = Orders::find()->where(['order_no' => $order['order_no']])->asArray()->all();
        $car = Cars::find()->where(['id' => $order['car_id']])->asArray()->one();
        $member = Members::find()->where(['id' => $car['member_id']])->asArray()->one();
        $order_goods = Order_goods::find()->where(['order_no' => $order['order_no']])->asArray()->all();
        foreach($order_goods as $key=>$val):
            $order_goods[$key]['goods'] = Goods::find()->where(['goods_id' => $val['goods_id']])->asArray()->one();
        endforeach;
        $user = User::find()->where(['id' => $order['payee_id']])->asArray()->one();
        $worker = User::find()->where(['id' => $order['worker_id']])->asArray()->one();
        $examine = User::find()->where(['id' => $order['examine']])->asArray()->one();
        if($order['package_id'] != 0){
            $package = Package::find()->where(['id' => $order['package_id']])->asArray()->one();
        }
        return $this->render('detail',[
            'order_no' => $order['order_no'],
            'orders' => $orders,
            'order_goods' => $order_goods,
            'car' => $car,
            'member' => $member,
            'worker' => $worker,
            'examine' => $examine,
            'order' => $order,
            'order_id' => $order_id,
            'user' => $user,
            'package' => $package,
        ]);
    }

    public function beforeAction($action)
    {
        if(Yii::$app->session['user_role']['orders'] != 'on'){
            Yii::$app->getSession()->setFlash('error','你没有权限访问！');
            return $this->goHome();
        }else{
            return $action;
        }
    }

    //前台交车
    public function actionGet_car(){
        $order_no = $_POST['order_no'];
        $orders = Orders::find()->where(['order_no' => $order_no])->all();
        foreach($orders as $order):
            $order->get_time = time();
            $order->save();
        endforeach;
        echo 111;exit;
    }

    //前台修改客户订单商品
    public function actionGet_goods(){
        $order_no = $_POST['order_no'];
        //根据订单号查找订单下面的所有服务
        $orders = Orders::find()->where(['order_no' => $order_no])->asArray()->all();
        //查所有服务需要用到的产品的类型码,然后按照类型查所需要的产品列表
        foreach($orders as $order):
            $service_goods = Service_goods::find()->where(['service_id' => $order['service_id']])->asArray()->all();
//            echo $order['service_name'];
            foreach($service_goods as $service_good):
                $goods_list = Goods::find()->where(['goods_type' => $service_good['goods_type']])->asArray()->all();
                $goods_type = Goods_type::find()->where(['type_id' => $service_good['goods_type']])->asArray()->one();
                echo $goods_type['name'];
                foreach($goods_list as $goods):
                    echo "<p><input type='checkbox' name='goods_ids[]'>".$goods['goods_name']."</p>";
                endforeach;
            endforeach;
        endforeach;
        exit;
    }

    //前台删除已经选择了的商品
    public function actionDel_goods(){
        if(Yii::$app->request->post()){
            if($order_goods = Order_goods::deleteAll(['id' => $_POST['id']])){
                echo 111;
            }else{
                echo 222;
            }
        }
    }

    //查等待时间
    public function actionCheck_time(){
        $order_no = $_POST['order_no'];
        $order = Orders::find()->where(['order_no' => $order_no])->orderBy('create_time')->asArray()->one();
        //查店铺下面排在本工单前，还未开工的所有工单
        $pev_orders = Orders::find()->where('status < 20')->andWhere(['store_id' => $order['store_id']])->andWhere("create_time <".$order['create_time'])->asArray()->all();
        //循环所有工单，计算所有服务的标准时间
        $need_time = 0;
        $need_car = 0;
        foreach($pev_orders as $val):
            $service = Service::find()->where(['id' => $val['service_id']])->asArray()->one();
            $need_time += $service['use_time']+5;
            $need_car++;
        endforeach;
        //计算正在开工的订单还剩下多少时间
        $begin_order = Orders::find()->where(['status' => 20])->andWhere(['store_id' => $order['store_id']])->asArray()->one();
        if(!empty($begin_order)) {
            $begin_orders = Orders::find()->where(['order_no' => $begin_order['order_no']])->asArray()->all();
            $has_time = 0;
            foreach($begin_orders as $val):
                $service = Service::find()->where(['id' => $val['service_id']])->asArray()->one();
                $has_time += $service['use_time']+5;
            endforeach;
            $has_time_2 = time() - $begin_order['begin_time'];  //已经开始了多少时间
            $has_time_2 = $has_time_2/60;
            $has_time = $has_time - $has_time_2;
        }
        if($has_time < 0){
            $has_time = 0;
            $need_car++;
        }
//        echo $begin_has_use_time;exit;
        $need_time += $has_time+5;
        echo "该车辆前面还有".$need_car."辆车在排队，";
        echo "它的排队时间大约是".$need_time."分钟";
        exit;
    }

    //取消订单，记录取消人信息
    public function actionDel_order(){
        if(Yii::$app->request->post()){
            $order_id = $_POST['order_id'];
            $user_id = Yii::$app->session['user_id'];
            if(empty($order_id) || empty($user_id)){
                echo 333;
                exit;
            }
            $order = Orders::find()->where(['id' => $order_id])->one();
//            if($order->status != 11){
//                echo 333;
//                exit;
//            }
            Order_goods::deleteAll(['order_id' => $order['id']]);
            if(Orders::deleteAll(['id' => $order['id']])){
                echo 111;exit;
            }else{
                echo 222;exit;
            }
        }
    }

    //结账
    public function actionTo_payment(){
        $order_id = $_GET['order_no'];
        $orders = Orders::find()->where(['order_no' => $order_id])->andWhere(['status' => 30])->asArray()->all();
        $order = current($orders);
        $car = Cars::find()->where(['id' => $order['car_id']])->asArray()->one();
        //计算总工费
        $worker_price = 0;//工时费
        //包含套餐
        $package_id = array();
        foreach($orders as $val):
            $worker_price += $val['price'];
            if($val['package_id'] != 0 && !empty($val['package_id'])){
                $package_id[] = $val['package_id'];
            }
        endforeach;
        if(count($package_id) > 0){
            $package_id = implode(',',$package_id);
            $packages = Package::find()->where("id in (".$package_id.")")->asArray()->all();
        }
        $goods = Order_goods::find()->where(['order_no' => $order_id])->asArray()->all();

        $total_price = $worker_price;
        $goods_ids = array();
        //循环商品计算总价
        foreach($goods as $good):
            if($good['package_id'] == 0 || empty($good['package_id'])) {
                $total_price += $good['price'] * $good['nums'];
                if(!empty($good['goods_id'])){
                    $goods_ids[] = $good['goods_id'];
                }
            }
        endforeach;
        if($total_price == 0){
            $total_price = $order['price'];
        }
        $member = Members::find()->where(['id' => $car['member_id']])->asArray()->one();
        $has_discount = array();
        //计算是否可以用余额支付
        if($member['balance'] > 0){
            $has_discount['balance'] = array(
                'name' => '余额支付',
                'value' => '￥'.$member['balance'],
            );
        }
        //查是否有现金卷可以使用
        if(($goods_ids)){
            $coupon_ids = array();
            //查订单有该商品的或者通用的coupon
            $goods_ids = implode(',',$goods_ids);
            $coupon = Coupon::find()->where("goods_id in (".$goods_ids.")")->orWhere(['goods_id' => 0])->andWhere("min_price <=".$total_price)->asArray()->all();
            foreach($coupon as $val):
                $coupon_ids[] = $val['coupon_id'];
            endforeach;
        }else{
            $coupon = Coupon::find()->where(['goods_id' => 0])->andWhere("min_price <=".$total_price)->asArray()->all();
            foreach($coupon as $val):
                $coupon_ids[] = $val['coupon_id'];
            endforeach;
        }
        if(!empty($coupon_ids)){
            $coupon_ids = implode(',', $coupon_ids);
            $member_coupon = Member_coupon::find()->where("coupon_id in (" . $coupon_ids . ")")->andWhere(['status' => 2])->andWhere("end_time >" . time())->andWhere(['member_id' => $member['id']])->asArray()->count();
        }
        if($member_coupon > 0){
            $has_discount['coupon'] = array(
                'name' => '现金卷抵扣',
                'value' => $member_coupon.'张可用',
            );
        }
        //计算是否有会员优惠
        $discount = Member_discount::find()->where(['member_type' => $member['type']])->asArray()->one();
        if($discount['discount'] < 1){
            $has_discount['discount'] = array(
                'name' => '会员折扣',
                'value' => ($discount['discount']*10)."折",
            );
        }
        //计算消费积分
        $cons_point = Member_cons_point::find()->where(['member_id' => $member['id']])->andWhere(['>','surplus',0])->andWhere(['>','ev_time',time()])->asArray()->all();
        $can_cons_point = 0;
        if(!empty($cons_point)) {
            foreach ($cons_point as $val):
                $can_cons_point += $val['surplus'];
            endforeach;
        }
        if($can_cons_point > 0){
            $has_discount['cons_point'] = array(
                'name' => '消费积分抵现',
                'value' => $can_cons_point."分",
            );
        }
        //计算出客户账号可以用的折扣方式，消费积分、代金卷、会员等级
        return $this->render('order_payment',[
            'packages' => $packages,
            'order' => $order,
            'orders' => $orders,
            'goods' => $goods,
            'car' => $car,
            'member' => $member,
            'worker_price' => $worker_price,
            'total_price' => $total_price,
            'can_cons_point' => $can_cons_point,
            'has_discount' => $has_discount,
            'discount' => $discount,
            'coupon' => $member_coupon,
        ]);
    }

    //根据传入的订单号搜索可用coupon
    public function actionGet_coupon_by_order_no(){
        if(Yii::$app->request->post()){
            $order_no = $_POST['order_no'];
            $total_price = $_POST['total_price'];
            //先查订单下面所有的商品
            $order_goods = Order_goods::find()->where(['order_no' => $order_no])->asArray()->all();
            //循环出goods_id
            $goods_ids = array();
            if(count($order_goods) > 0){
                foreach($order_goods as $good):
                    $goods_ids[] = $good['goods_id'];
                endforeach;
            }
            //查是否有现金卷可以使用
            if(($goods_ids)){
                $coupon_ids = array();
                //查订单有该商品的或者通用的coupon
                $goods_ids = implode(',',$goods_ids);
                $coupon = Coupon::find()->where("goods_id in (".$goods_ids.")")->orWhere(['goods_id' => 0])->andWhere("min_price <=".$total_price)->asArray()->all();
                foreach($coupon as $val):
                    $coupon_ids[] = $val['coupon_id'];
                endforeach;
            }else{
                $coupon = Coupon::find()->where(['goods_id' => 0])->andWhere("min_price <=".$total_price)->asArray()->all();
                foreach($coupon as $val):
                    $coupon_ids[] = $val['coupon_id'];
                endforeach;
            }
            if(!empty($coupon_ids)){
                $coupon_ids = implode(',', $coupon_ids);
                $member_coupon = Member_coupon::find()->where("coupon_id in (" . $coupon_ids . ")")->andWhere(['status' => 2])->andWhere("end_time >" . time())->asArray()->all();
                echo "<select id='coupon_id'>";
                foreach($member_coupon as $coupon):
                    $the_coupon = Coupon::find()->where(['coupon_id' => $coupon['coupon_id']])->asArray()->one();
                    echo "<option value='".$coupon['id']."'>".$coupon['coupon_sn']."(￥".$the_coupon['price'].")"."</option>";
                endforeach;
                echo "</select>";
            }else{
                echo "您账号没有可用的现金卷";
            }
            exit;
        }
    }

    //根据coupon_id查抵用现金
    public function actionGet_coupon_price(){
        if(Yii::$app->request->post()){
            $coupon_no = $_POST['coupon_id'];
            $member_coupon = Member_coupon::find()->where(['id' => $coupon_no])->asArray()->one();
            $coupon = Coupon::find()->where(['coupon_id' => $member_coupon['coupon_id']])->asArray()->one();
            echo $coupon['price'];
            exit;
        }
    }

    //前台接客户自己下的工单
    public function actionGet_order(){
        if(Yii::$app->request->post()){
            $order_no = $_POST['order_no'];
            if(empty($order_no)){
                echo 333;
                exit;
            }
            //所有相同的未接的工单做操作
            $orders = Orders::find()->where(['order_no' => $order_no])->andWhere(['status' => 10])->all();
            if(empty($orders)){
                echo 333;
                exit;
            }
            $appointment_id = 0;
            foreach($orders as $order):
                $order->status = 11;
                $order->store_id = Yii::$app->session['store_id'];
                if(!empty($order['appointment_id']))
                $appointment_id = $order['appointment_id'];
                $order->save();
            endforeach;
            if($appointment_id != 0) {
                //发送微信通知
                $member = Members::find()->where(['id' => $appointment_id])->asArray()->one();
                if(!empty($member['open_id'])){
                    //先查接单门店
                    $user = User::find()->where(['id' => Yii::$app->session['user_id']])->asArray()->one();
                    $store = Store::find()->where(['store_id' => $user['store_id']])->asArray()->one();
                    //再查排队情况
                    $return_data = $this->find_cars($order_no);
                    $appid = $member['weixin_id'];
                    $template = Weixin_template::find()->where(['type_id' => 5])->andWhere(['appid' => $appid])->asArray()->one();
                    $weixin = Weixin::find()->where(['appid' => $appid])->asArray()->one();
                    $data = array(
                        'first' => array('value' => '门店已经受理了您的预约单',),
                        'keyword1' => array('value' => $store['store_name'],'topcolor'=> '#0F12D'),
                        'keyword2' => array('value' => date("Y-m-d H:i:s",time()),'topcolor'=> '#0F12D'),
                        'keyword3' => array('value' => $user['username'],'topcolor'=> '#0F12D'),
                        'keyword4' => array('value' => $return_data['need_car']."辆",'topcolor'=> '#0F12D'),
                        'keyword5' => array('value' => ceil($return_data['need_time']).'分钟','topcolor'=> '#0F12D'),
                        'remark' => array('value' => '预计您'.ceil($return_data['need_time']).'分钟后可以到店进行养护','topcolor'=> '#0F12D'),
                    );
                    $error_code = $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$weixin['access_token'],$data);
                    if($error_code != 0){
                        $access_token = $this->get_access_token($appid,$weixin['app_secret']);
                        if($access_token != false){
                            $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$access_token,$data);
                        }
                    }
                }
            }
            echo 111;
            exit;
        }
    }

    //预约单计算等待车辆以及时间
    public function find_cars($order_no){
//        $order_no = $_POST['order_no'];
        $order = Orders::find()->where(['order_no' => $order_no])->orderBy('create_time')->asArray()->one();
        //查店铺下面排在本工单前，还未开工的所有工单
        $pev_orders = Orders::find()->where('status < 20')->andWhere(['store_id' => $order['store_id']])->andWhere("create_time <".$order['create_time'])->asArray()->all();
        //循环所有工单，计算所有服务的标准时间
        $need_time = 0;
        $need_car = 0;
        foreach($pev_orders as $val):
            $service = Service::find()->where(['id' => $val['service_id']])->asArray()->one();
            $need_time += $service['use_time']+5;
            $need_car++;
        endforeach;
        //计算正在开工的订单还剩下多少时间
        $begin_order = Orders::find()->where(['status' => 20])->andWhere(['store_id' => $order['store_id']])->asArray()->one();
        if(!empty($begin_order)) {
            $begin_orders = Orders::find()->where(['order_no' => $begin_order['order_no']])->asArray()->all();
            $has_time = 0;
            foreach($begin_orders as $val):
                $service = Service::find()->where(['id' => $val['service_id']])->asArray()->one();
                $has_time += $service['use_time']+5;
            endforeach;
            $has_time_2 = time() - $begin_order['begin_time'];  //已经开始了多少时间
            $has_time_2 = $has_time_2/60;
            $has_time = $has_time - $has_time_2;
        }
        if($has_time < 0){
            $has_time = 0;
            $need_car++;
        }
//        echo $begin_has_use_time;exit;
        $need_time += $has_time+5;
        $return_data = array(
            'need_time' => $need_time,
            'need_car' => $need_car,
        );
        return $return_data;
    }

    //预约订单
    public function actionAppointment(){
        if(Yii::$app->request->post()){
            $order_no = $_POST['order_no'];
            $car_no = $_POST['car_no'];
            if(empty($order_no) && empty($car_no)){
                $orders = null;
            }else {
                $all_orders = Orders::find()->where(['status' => 10]);
                if (!empty($order_no)) {
                    $all_orders->andWhere(['order_no' => $order_no]);
                }
                if (!empty($car_no)) {
                    $car = Cars::find()->where(['car_no' => $car_no])->asArray()->one();
                    $all_orders->andWhere(['car_id' => $car['id']]);
                }
                $orders = $all_orders->asArray()->all();
                foreach($orders as $key=>$order):
                    $orders[$key]['car'] = Cars::find()->where(['id' => $order['car_id']])->asArray()->one();
                endforeach;
            }


            return $this->render('appointment',[
                'orders' => $orders,
                'order_no' => $order_no,
                'car_no' => $car_no,
            ]);
        }else{
            return $this->render('appointment');
        }
    }



    //导出cvs
    public function actionExport_orders(){
            $order_no = $_GET['order_no'];
            $status = $_GET['status'];
            $begin_date = $_GET['begin_date'];
            $end_date = $_GET['end_date'];
            if(empty($end_date)){
                $end_date = date("Y-m-d",(time()+86400));
            }
            $all_orders = Orders::find()->orWhere(['store_id' => Yii::$app->session['store_id']])->orderBy(['create_time' => SORT_DESC]);
            if(!empty($order_no)){
                $all_orders->andWhere(['order_no' => $order_no]);
            }
            if(!empty($status)){
                $all_orders->andWhere(['status' => $status]);
            }
            if(!empty($begin_date)){
                $begin_time = strtotime($begin_date);
                $all_orders->andWhere("create_time >=".$begin_time);
            }
            $end_time = strtotime($end_date);
            $all_orders->andWhere("create_time < ".$end_time);
            $all_orders->all();
            $orders = $all_orders->asArray()->all();
            foreach($orders as $key=>$order):
                $orders[$key]['car'] = Cars::find()->where(['id' => $order['car_id']])->asArray()->one();
                $goods = Order_goods::find()->where(['order_id' => $order['id']])->asArray()->all();
                $goods_price = 0;
                foreach($goods as $good):
                    $goods_price += ($good['price']*$good['nums']);
                endforeach;
                $orders[$key]['goods_price'] = $goods_price;
                $orders[$key]['worker'] = User::find()->where(['id' => $order['worker_id']])->asArray()->one();
            endforeach;
            if($orders){
                $str = "工单号,车牌号,服务,施工开始时间,时间结束时间,施工工人,工时费,材料费,实收\n";
                $str = iconv('utf-8','gb2312',$str);
                foreach( $orders as $order ) {
                    $order_no = $order['order_no'];
                    $car_no = iconv('utf-8', 'gb2312', $order['car']['car_no']);
                    $service = iconv('utf-8', 'gb2312', $order['service_name']);
                    $b_time = date("Y-m-d H:i:s", $order['begin_time']);
                    $f_time = date("Y-m-d H:i:s", $order['finish_time']);
                    $worker = iconv('utf-8', 'gb2312', $order['worker']['username']);
                    $worker_price = $order['price'];
                    $goods_price = $order['goods_price'];
                    $realy_price = $order['realy_price'];
                    $str .= $order_no . "," . $car_no . "," . $service . "," . $b_time . "," . $f_time . "," . $worker . "," . $worker_price . "," . $goods_price . "," . $realy_price . "\n";
                }
                $file_name = 'orders.'.date('Ymd').'.csv';
                $this->export_csv($file_name,$str);
            }
    }
}