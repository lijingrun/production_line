<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/11
 * Time: 21:12
 */
namespace backend\controllers;

use common\models\Additional_goods;
use common\models\Additional_orders;
use common\models\Car_reason;
use common\models\Car_reasonmodel;
use common\models\Car_type;
use common\models\Carema;
use common\models\Goods;
use common\models\Goods_type;
use common\models\Members;
use common\models\Order_goods;
use common\models\Package;
use common\models\Service;
use common\models\Service_check_type;
use common\models\Service_goods;
use common\models\Service_price;
use common\models\Service_type;
use common\models\Weixin;
use common\models\Weixin_template;
use Yii;
use yii\web\Controller;
use common\models\Orders;
use common\models\Cars;
use common\models\User;

class WorkerController extends Controller{

    //工人接单
    public function actionOrder_taking(){
        $id = $_GET['id'];
        $user_id = Yii::$app->session['user_id'];
        if(empty($id) || empty($user_id)){
            echo "没有权限！";
            exit;
        }
        $user = User::find()->where(['id' => $user_id])->asArray()->one();
        if($user['type_id'] != 2){
            echo "你不是工人";
            exit;
        }
        //判断是否还有未完成的工单
        $my_order = Orders::find()->where(['status' => 20])->andWhere(['worker_id' => Yii::$app->session['user_id']])->asArray()->one();
        if(!empty($my_order['id'])){
            echo "你还有工单未完成，不能重复接单！";
            exit;
        }
        $orders = Orders::find()->where(['order_no' => $id])->all();
        foreach($orders as $order):
            $order->checked_time = time();
            $order->worker_id = $user_id;
            $order->status = 12;  //接单
            $order->save();
        endforeach;
//        if($order->save()){
            Yii::$app->getSession()->setFlash('success','接单成功！');
            return $this->redirect('index.php?r=worker/my_order');
//        }else{
//            Yii::$app->getSession()->setFlash('error','接单失败，请重试');
//            return $this->redirect('index.php');
//        }
    }

    //工人订单界面
    public function actionMy_order(){
        $orders = Orders::find()->where(['status' => 20])->orWhere(['status' => 12])->andWhere(['worker_id' => Yii::$app->session['user_id']])->asArray()->all();
        //获取第一个，并用来获取车辆信息
        $order = current($orders);
        foreach($orders as $val):
            if($val['package_id'] != 0){
                $package_ids[] = $val['package_id'];
            }
        endforeach;
        if(!empty($package_ids)){
            $package_ids = implode(',',$package_ids);
            $packages = Package::find()->where("id in (".$package_ids.")")->asArray()->all();
        }
        //如果是检测中，查询需要检测的项目
        if($order['status'] == 12){
            $check_types = Service_check_type::find()->asArray()->all();
            $checked_time = time() - $order['checked_time'];
            $checked_time = ceil($checked_time/60);
        }
        if(empty($order['finish_time'])){
            $had_use_time = time() - $order['begin_time'];
        }else{
            $had_use_time = $order['finish_time'] - $order['begin_time'];
        }
        $had_use_time = ceil($had_use_time/60);
        //查加单申请
        $car_resons = Car_reason::find()->where(['car_id' => $order['car_id']])->andWhere(['order_no' => $order['order_no']])->asArray()->all();
        $car = Cars::find()->where(['id' => $order['car_id']])->asArray()->one();
        $goods = Order_goods::find()->where(['order_no' => $order['order_no']])->asArray()->all();
		foreach($goods as $key=>$good):
            $goods[$key]['goods'] = Goods::find()->where(['goods_id' => $good['goods_id']])->asArray()->one();
        endforeach;
        return $this->render('order_detail',[
            'orders' => $orders,
            'order' => $order,
            'had_use_time' => $had_use_time,
            'checked_time' => $checked_time,
            'order_goods' => $goods,
            'types' => $check_types,
            'car_reasons' => $car_resons,
            'car' => $car,
            'packages' => $packages,
        ]);
    }

    //开始施工
    public function actionBegin_to_work(){
        if(Yii::$app->request->post()){
            $order_id = $_POST['order_id'];
            $orders = Orders::find()->where(['order_no' => $order_id])->andWhere(['status' => 12])->all();
            $use_time = 0;
            foreach($orders as $order){
                $order->begin_time = time();
                $order->status = 20;
                $car_id = $order['car_id'];
                //计算服务总时间
                $service = Service::find()->where(['id' => $order['service_id']])->asArray()->one();
                $use_time += $service['use_time'];
                $worker_id = $order['worker_id'];
                $order->save();
            }
            $car = Cars::find()->where(['id' => $car_id])->asArray()->one();
            $member = Members::find()->where(['id' => $car['member_id']])->asArray()->one();
            if(!empty($member['open_id'])){
                $use_time = $use_time*60;
                //查师傅
                $worker = User::find()->where(['id' => $worker_id])->asArray()->one();
                $appid = $member['weixin_id'];
                $template = Weixin_template::find()->where(['type_id' => 7])->andWhere(['appid' => $appid])->asArray()->one();
                $weixin = Weixin::find()->where(['appid' => $appid])->asArray()->one();
                $data = array(
                    'first' => array('value' => '您的爱车已经开始保养，您可随时查看施工工位现场！',),
                    'keyword1' => array('value' => $worker['username'],'topcolor'=> '#0F12D'),
                    'keyword2' => array('value' => date("Y-m-d H:i:s",time()),'topcolor'=> '#0F12D'),
                    'keyword3' => array('value' => date("Y-m-d H:i:s",time()+$use_time),'topcolor'=> '#0F12D'),
                    'remark' => array('value' => '如果有什么疑问，欢迎咨询我们','topcolor'=> '#0F12D'),
                );
                $error_code = $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$weixin['access_token'],$data);
                if($error_code != 0){
                    $access_token = $this->get_access_token($appid,$weixin['app_secret']);
                    if($access_token != false){
                        $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$access_token,$data);
                    }
                }
            }
            echo 111;
            exit;
        }
    }

    public function beforeAction($action){
        if(empty(Yii::$app->session['user_id'])){
            return $this->redirect('index.php');
        }else{
            return $action;
        }
    }

    //更新订单的维修里程
    public function actionInput_mileage(){
        if(Yii::$app->request->post()){
            $order_no = $_POST['order_id'];
            $mileage = $_POST['mileage'];
            $orders = Orders::find()->where(['order_no' => $order_no])->all();
            foreach($orders as $order):
                $order->mileage = $mileage;
                $order->save();
            endforeach;
            echo 111;
            exit;
        }
    }

    //添加车辆备注
    public function actionAdd_remarks(){
        if(Yii::$app->request->post()){
            $car_id = $_POST['car_id'];
            $remarks = $_POST['remarks'];
            $car = Cars::find()->where(['id' => $car_id])->one();
            $car->remarks = $remarks;
            if($car->save()){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //完成工单
    public function actionFinish_order(){
        if(Yii::$app->request->post()){
            $order_id = $_POST['order_id'];
            $orders = Orders::find()->where(['order_no' => $order_id])->andWhere(['status' => 20])->all();
            $car_id = 0;
            $car_mileage = 0;
            $out_time_reason = $_POST['reason'];
            //查所有工时的标准工时，对比实际工时，若超期，需要填超期时间
            $stand_time = 0;
            $use_time = 0;
            foreach($orders as $order):
                $use_time = time() - $order['begin_time'];
                $service = Service::find()->where(['id' => $order['service_id']])->asArray()->one();
                $stand_time += $service['use_time']*60*60; //标准工时（秒）
            endforeach;
            $out_time = $use_time - $stand_time;
            //如果超期，并且无原因，重填
            if($out_time > 0 && empty($out_time_reason)){
                echo 333;
                exit;
            }
            if($out_time < 0){
                $out_time = 0;
            }
            foreach($orders as $order):
                $order->finish_time = time();
                $order->status = 21;
                $order->out_time = $out_time;
                $order->out_time_reason = $out_time_reason;
                $order->save();
                $car_id = $order['car_id'];
                $car_mileage = $order['mileage'];
            endforeach;
            //查是否有为确定的加单申请，有的话全部变成取消状态
            $reasons = Car_reason::find()->where(['car_id' => $car_id])->andWhere(['status' => 1])->all();
            foreach($reasons as $reason):
                $reason->status = 0;
                $reason->give_up_time = time();
                $reason->save();
                Additional_orders::deleteAll(['reason_id' => $reason['id']]);
                Additional_goods::deleteAll(['reason_id' => $reason['id']]);
            endforeach;

            //更新车辆保养信息
            $car = Cars::find()->where(['id' => $car_id])->one();
            $car->before_mileage = $car->last_mileage;
            $car->before_maintain = $car->last_maintain;
            $car->last_mileage = $car_mileage;
            $car->last_maintain = time();
            $car->save();
            echo 111;exit;
        }
    }

    //工人选择摄像头
    public function actionChoose_carema(){
        $caremas = Carema::find()->where(['store_id' => Yii::$app->session['store_id']])->asArray()->all();
        if(Yii::$app->request->post()){
            $carema_id = $_POST['carema_id'];
            $carema = Carema::find()->where(['id' => $carema_id])->one();
            $carema->worker_id = Yii::$app->session['user_id'];
            if($carema->save()){
                return $this->redirect("index.php");
            }
        }else{
            return $this->render('choose_carema',[
                'caremas' => $caremas,
            ]);
        }
    }

    //工人验收
    public function actionCheck_work(){
        if(Yii::$app->request->post()){
            $order_sn = $_POST['order_sn'];
            $orders = Orders::find()->where(['order_no' => $order_sn])->andWhere(['status' => 21])->all();
            $worker_price = 0;
            foreach($orders as $val):
                if($val->worker_id == Yii::$app->session['user_id']){
                    echo 222;
                    exit;
                }
                $car_id = $val->car_id;
                $worker_id = $val->worker_id;
                $worker_price += $val->price;
                $val->status = 30;
                $val->examine = Yii::$app->session['user_id'];
                $val->examine_time = time();
                $val->save();
            endforeach;
            $car = Cars::find()->where(['id' => $car_id])->asArray()->one();
            $member = Members::find()->where(['id' => $car['member_id']])->asArray()->one();
            if(!empty($member['open_id'])){
                //计算总金额
                $total_price = 0;
                $goods = Order_goods::find()->where(['order_no' => $order_sn])->asArray()->all();
                foreach($goods as $good):
                    $total_price += $good['price'];
                endforeach;
                $total_price += $worker_price;
                $appid = $member['weixin_id'];
                $template = Weixin_template::find()->where(['type_id' => 8])->andWhere(['appid' => $appid])->asArray()->one();
                $weixin = Weixin::find()->where(['appid' => $appid])->asArray()->one();
                //施工人员
                $worker = User::find()->where(['id' => $worker_id])->asArray()->one();
                $check_man = User::find()->where(['id' => Yii::$app->session['user_id']])->asArray()->one();
                $data = array(
                    'first' => array('value' => '您的所有服务已经全部完工，请来店取车',),
                    'keyword1' => array('value' => "￥".$total_price,'topcolor'=> '#0F12D'),
                    'keyword2' => array('value' => date("Y-m-d H:i:s",time()),'topcolor'=> '#0F12D'),
                    'keyword3' => array('value' => $worker['username'],'topcolor'=> '#0F12D'),
                    'keyword4' => array('value' => $check_man['username'],'topcolor'=> '#0F12D'),
                    'remark' => array('value' => '亲，您现在可以到店来取车了！','topcolor'=> '#0F12D'),
                );
                $error_code = $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$weixin['access_token'],$data);
                if($error_code != 0){
                    $access_token = $this->get_access_token($appid,$weixin['app_secret']);
                    if($access_token != false){
                        $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$access_token,$data);
                    }
                }
            }
            echo 111;
            exit;
        }
    }
    public $enableCsrfValidation = false;
    //追加工单
    public function actionAdditional(){
        $order_no = $_GET['order_no'];
        $order = Orders::find()->where(['order_no' => $order_no])->andWhere(['status' => 20])->asArray()->one();
        if(empty($order)){
            Yii::$app->getSession()->setFlash('error','该工单不存在/工单不在工程中');
            return $this->redirect('index.php?r=worker/my_order');
        }
        $car = Cars::find()->where(['id' => $order['car_id']])->asArray()->one();
        //类型
        $service_type = Service_type::find()->asArray()->all();
        if(Yii::$app->request->post()){
            $service_id = $_POST['service_id'];
            $reason = $_POST['reason'];
            $service = Service::find()->where(['id' => $service_id])->asArray()->one();
            //添加提醒
            $new_reason = new Car_reason();
            $new_reason->worker_id = Yii::$app->session['user_id'];
            $new_reason->reason = $reason;
            $new_reason->car_id = $car['id'];
            $new_reason->service_id = $service_id;
            $new_reason->service_name = $service['name'];
            $new_reason->status = 1;
            $new_reason->store_id = Yii::$app->session['store_id'];
            $new_reason->create_time = time();
            $new_reason->car_no = $car['car_no'];
            $new_reason->order_no = $order_no;
            if($new_reason->save()){
//                $member = Members::find()->where(['id' => $car['member_id']])->asArray()->one();
//                //绑定微信，就发通知
//                if(!empty($member['open_id'])){
//                    $data = array(
//                        'first' => array('value' => '您有一个加单申请,请登录系统到工单池里面进行加单操作！','topcolor'=> '#0F12D'),
//                        'keyword1' => array('value' => $car['car_no'],'topcolor'=> '#0F12D'),
//                        'keyword2' => array('value' => $reason ,'topcolor'=> '#0F12D'),
//                        'remark' => array('value' => '为了我们能及时帮您处理问题，请在3小时内完成加单，否则我们会当作您放弃加单' ,'topcolor'=> '#0F12D'),
//                    );
//                    //根据客户所在的公众号appid获取需要的微信信息
//                    $weixin = Weixin::find()->where(['appid' => $member['weixin_id']])->asArray()->one();  //公众号
//                    $template = Weixin_template::find()->where(['appid' => $member['weixin_id']])->andWhere(['type_id' => 3])->asArray()->one();
//                    $appid = $weixin['appid'];
//                    $secret = $weixin ['app_secret'];
//                    $errcode = $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$weixin['access_token'],$data);
//                    //access_token无效的话，重新获取
//                    if($errcode == 41006 || $errcode == 41001 || $errcode == 42001 || $errcode == 40014){
//                        $access_token = $this->get_access_token($appid,$secret);
//                        if($access_token != false){
//                            $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$access_token,$data);
//                        }
//                    }
//                }
                Yii::$app->getSession()->setFlash('success','提醒成功，正在等待客户加单');
            }else{
                Yii::$app->getSession()->setFlash('error','服务器繁忙，请稍后重试');
            }
//            //查类型
//            $service = Service::find()->where(['id'=>$service_id])->asArray()->one();
//            //查对应工时费
//            $service_price = Service_price::find()->where(['service_id' => $service_id])->andWhere(['car_type' => $car['type']])->asArray()->one();
//            //如果无对应类型的工时费，直接查所有类型噶工时费
//            if(empty($service_price)){
//                $service_price = Service_price::find()->where(['service_id' => $service_id])->andWhere(['car_type' => 0])->asArray()->one();
//            }
//            $service_price = $service_price['price'];
//            if(empty($service_price)){
//                $service_price = 0;
//            }
//            $new_order = new Orders();
//            $new_order->order_no = $order_no;
//            $new_order->car_id = $car['id'];
//            $new_order->service_id = $service_id;
//            $new_order->create_time = time();
//            $new_order->store_id = Yii::$app->session['store_id'];
//            $new_order->worker_id = $order['worker_id'];
//            $new_order->status = 13;
//            $new_order->mileage = $order['mileage'];
//            $new_order->create_id = Yii::$app->session['user_id'];
//            $new_order->begin_time = $order['begin_time'];
//            $new_order->service_name = $service['name'];
//            $new_order->price = $service_price;
//            $new_order->reason = $_POST['reason'];
//            if($new_order->save()){
//                $goods_ids = $_POST['goods_ids'];
//                if(!empty($goods_ids)) {
//                    foreach ($goods_ids as $goods_id):
//                        if ($goods_id != 0) {
//                            $goods = Goods::find()->where(['goods_id' => $goods_id])->asArray()->one();
//                            $service_goods = Service_goods::find()->where(['service_id' => $service_id])->andWhere(['goods_type' => $goods['goods_type']])->asArray()->one();
//                            $order_goods = new Order_goods();
//                            $order_goods->order_no = $order_no;
//                            $order_goods->goods_id = $goods_id;
//                            $order_goods->goods_name = $goods['goods_name'];
//                            $order_goods->price = $goods['price'];
//                            $order_goods->nums = $service_goods['nums'];
//                            $order_goods->save();
//                        }
//                    endforeach;
//                }
//                Yii::$app->getSession()->setFlash('success','提醒成功，等待客户自主加单！');
//            }else{
//                Yii::$app->getSession()->setFlash('error','系统繁忙，请稍后重试');
//            }
            return $this->redirect('index.php?r=worker/my_order');
        }else{
            return $this->render('additional',[
//                'order' => $order,
                'car' => $car,
                'service_types' => $service_type,
            ]);
        }
    }

    //根据类型获取服务
    public function actionFind_service(){
        if(Yii::$app->request->post()){
            $type_id = $_POST['type_id'];
            $services = Service::find()->where(['type_id' => $type_id])->asArray()->all();
            echo "<select name='service_id' id='service_id' onchange='get_goods();'>";
            echo "<option value=\"0\">请选择服务类型</option>";
            foreach($services as $service):
                echo "<option value='".$service['id']."'>".$service['name']."</option>";
            endforeach;
            echo "</select>";
            exit;
        }
    }

    //根据服务类型获取需要的商品
    public function actionGet_goods(){
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

}