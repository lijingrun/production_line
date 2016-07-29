<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 16:46
 */
namespace frontend\controllers;


use common\models\Car_brand;
use common\models\Car_model;
use common\models\Members;
use common\models\Car_style;
use common\models\Car_type;
use common\models\Car_typeForm;
use common\models\Goods;
use common\models\Goods_type;
use common\models\Order_back;
use common\models\Orders;
use common\models\Service_goods;
use common\models\Service;
use common\models\Order_goods;
use common\models\Cars;
use common\models\Service_price;
use common\models\Service_type;
use yii\data\Pagination;
use Yii;
use yii\web\Controller;

class CarController extends Controller{


    public function beforeAction($action)
    {
        if(Yii::$app->session['user_role']['car'] != 'on'){
            Yii::$app->getSession()->setFlash('error','你没有权限访问！');
            return $this->goHome();
        }else{
            return $action;
        }
    }

    public function actionIndex(){
        $car_no = $_GET['car_no'];
        $all_car = Cars::find();
        if(!empty($car_no)){
            $all_car->andWhere(['like', 'car_no', $car_no]);
        }
        $pages = new Pagination([
            'totalCount' => $all_car->count(),
            'pageSize' => 8,
        ]);
        $cars = $all_car->offset($pages->offset)->limit($pages->limit)->all();
        foreach($cars as $key=>$car):
            $cars[$key]['car_type'] = Car_type::find()->where(['type_id' => $car['car_type']])->asArray()->one();
			$cars[$key]['remarks'] = Members::find()->where(['id' => $car['member_id']])->asArray()->one();
        endforeach;
        return $this->render('cars_list',[
            'cars' => $cars,
            'pages' => $pages,
            'car_no' => $car_no,
        ]);
    }

    //车辆详细资料
    public function actionDetail(){
        $car_id = $_GET['car_id'];
        $car = Cars::find()->where(['id' => $car_id])->asArray()->one();
        $types = Car_type::find()->asArray()->all();
//        $car['model'] = Car_model::find()->where(['id' => $car['brand']])->asArray()->one();
        if(Yii::$app->request->post()){
//            print_r($_POST);exit;
            $car = Cars::find()->where(['id' => $car_id])->one();
            $brand = Car_brand::find()->where(['brand_id' => $_POST['brand_id']])->asArray()->one();
            $car_model = Car_model::find()->where(['id' => $_POST['model_id']])->asArray()->one();
            $car_style = Car_style::find()->where(['id' => $_POST['style_id']])->asArray()->one();
            $car->car_no = $_POST['car_no'];
            $car->car_type = $_POST['car_type'];
            if(!empty($brand)) {
                $car->brand = $_POST['brand_id'];
                $car->brand_name = $brand['brand_name'];
            }
            if(!empty($car_model)) {
                $car->model_id = $_POST['model_id'];
                $car->model_name = $car_model['model_name'];
            }
            if(!empty($car_style)) {
                $car->style_id = $_POST['style_id'];
                $car->style_name = $car_style['style_name'];
            }
            $car->buy_year = $_POST['buy_year'];
            $car->car_code = $_POST['car_code'];
            $car->engine_type = $_POST['engine_type'];
            $car->remarks = $_POST['remarks'];
            if($car->save()){
                Yii::$app->getSession()->setFlash('success','修改成功！');
            }else{
                Yii::$app->getSession()->setFlash('error','服务器繁忙，请稍后重试！');
            }
            return $this->redirect('index.php?r=car/detail&car_id='.$car_id);
        }else{
            return $this->render('car_detail',[
                'car' => $car,
                'types' => $types,
            ]);
        }
    }

    //增加汽车类型
    public function actionType_add(){
        $car_types = Car_type::find()->asArray()->all();
        $model = new Car_typeForm();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $new_type = new Car_type();
            $new_type->car_type = $model['car_type'];
            if($new_type->save()){
                Yii::$app->getSession()->setFlash('success','登记成功！');
            }else{
                Yii::$app->getSession()->setFlash('error','登记失败，请重试！');
            }
            return $this->redirect('index.php?r=car/type_add');
        }else{
            return $this->render('car_type',[
                'model' => $model,
                'car_types' => $car_types,
            ]);
        }

    }

    //车辆工单信息
    public function actionOrders(){
        $car_id = $_GET['car_id'];
        $car = Cars::find()->where(['id' => $car_id])->asArray()->one();
        $all_orders = Orders::find()->where(['car_id' => $car_id])->orderBy(['create_time' => SORT_DESC]);
        $pages = new Pagination([
            'totalCount' => $all_orders->count(),
            'pageSize' => 20,
        ]);
        $orders = $all_orders->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('order_list',[
            'car' => $car,
            'pages' => $pages,
            'orders' => $orders,
        ]);
    }

    //为订单添加产品
    public function actionOrder_goods_add()
    {
        $id = $_GET['id'];
        $key_word = $_GET['key_word'];
        $order = Orders::find()->where(['id' => $id])->asArray()->one();
        $car = Cars::find()->where(['id' => $order['car_id']])->asArray()->one();
        //先查服务对应的产品的类型，再根据类型查找所有的对应车辆适用的产品给客户选择
        $service_goods = Service_goods::find()->where(['service_id' => $order['service_id']])->asArray()->all();
        $goods_type = array();
        foreach ($service_goods as $service_good):
            $goods_type[] = $service_good['goods_type'];
        endforeach;
        $goods_type = implode(',', $goods_type);
        if (!empty($goods_type)) {
//            $goods = Goods::find()->where("style_ids like '%," . $car['style_id'] . ",%'")->orWhere(['style_ids' => 'all'])->andWhere('goods_type in (' . $goods_type . ')')->asArray()->all();
            if(!empty($key_word)) {
                $goods = Goods::find()->where("style like '%" . $key_word . "%'")->orWhere("goods_name like '%" . $key_word . "%'")->andWhere('goods_type in (' . $goods_type . ')')->asArray()->all();
            }else{
                $goods = Goods::find()->where(['style_ids' => 'all'])->orWhere("style_ids like '%," . $car['style_id'] . ",%'")->andWhere('goods_type in (' . $goods_type . ')')->asArray()->all();
            }
        }
        //查找已经添加了的所有商品
        $has_add_goods = Order_goods::find()->where(['order_id' => $id])->asArray()->all();
//        print_r($has_add_goods);exit;
        if(Yii::$app->request->post()){
            $goods_ids = $_POST['goods_ids'];
            $goods_nums = $_POST['nums'];
            foreach($goods_ids as $key=>$goods_id){
                $the_goods = Goods::find()->where(['goods_id' => $goods_id])->asArray()->one();
                //判断是否需要匹配车型，需要的话判断匹配中是否有本车车型，没有的话就添加 上去
                if($the_goods['need_car_style'] == 1){
                    $goods_in_style = Goods::find()->where(['goods_id' => $goods_id])->andWhere("style_ids like '%,".$car['style_id'].",%'")->andWhere("style like '%.".$key_word.".%'")->asArray()->one();
                    if(empty($goods_in_style)){
                        $goods_in_style = Goods::find()->where(['goods_id' => $goods_id])->one();
                        $goods_style_ids = explode(',',$goods_in_style['style_ids']);
                        $goods_style_ids = array_filter($goods_style_ids);
                        $goods_style_ids = array_unique($goods_style_ids);
                        $goods_style_ids = implode(',',$goods_style_ids);
                        $goods_in_style['style_ids'] .= ",".$goods_style_ids.",";
                        $goods_in_style->save();
                    }
                }
                $new_order_goods = new Order_goods();
                $new_order_goods->order_no = $order['order_no'];
                $new_order_goods->order_id = $id;
                $new_order_goods->goods_id = $the_goods['goods_id'];
                $new_order_goods->goods_name = $the_goods['goods_name'];
                $new_order_goods->price = $the_goods['price'];
                $new_order_goods->nums = $goods_nums[$key];
                $new_order_goods->package_id = $order['package_id'];
                $new_order_goods->save();
            }
            Yii::$app->getSession()->setFlash('success','添加成功！');
            return $this->redirect('index.php?r=car/order_goods_add&id='.$id);
        }else{
            return $this->render('order_goods_add',[
                'goods' => $goods,
                'has_add_goods' => $has_add_goods,
                'id' => $id,
                'key_word' => $key_word,
            ]);
        }
    }

    //删除商品
    public function actionDel_goods(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            if(Order_goods::deleteAll(['id' => $id])){
                echo 111;
            }else{
                echo 222;
            }

        }
    }

    //复制订单
    public function actionCopy_order(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            $mg = $_POST['mg'];
            //根据id查订单以及订单包含的产品
            $old_order = Orders::find()->where(['id' => $id])->asArray()->one();
            $old_order_goods = Order_goods::find()->where(['order_id' => $old_order['id']])->asArray()->all();
            $order = new Orders();
            //查是否有还未开工的工单，有就用这些工单的工单号，无就新建一个
            $has_order = Orders::find()->where(['status' => 11])->andWhere(['car_id' => $old_order['car_id']])->asArray()->one();
            if(!empty($has_order)){
                $order_no = $has_order['order_no'];
            }else {
                $order_no = $order_no = $this->_get_order_no();
            }
            $order->order_no = $order_no;
            $order->service_id = $old_order['service_id'];
            $order->service_name = $old_order['service_name'];
            $order->create_time = time();
            $order->store_id = Yii::$app->session['store_id'];
            $order->car_id = $old_order['car_id'];
            $order->mileage = $mg;
            $order->status = 11;
            if($order->save()){
                foreach($old_order_goods as $goods):
                    $the_goods = Goods::find()->where(['goods_id' => $goods['goods_id']])->asArray()->one();
                    $order_goods = new Order_goods();
                    $order_goods->order_no = $order_no;
                    $order_goods->goods_id = $goods['goods_id'];
                    $order_goods->goods_name = $goods['goods_name'];
                    $order_goods->price = $the_goods['price'];
                    $order_goods->nums = $goods['nums'];
                    $order_goods->has_taked = 0;
                    $order_goods->order_id = $order['id'];
                    $order_goods->save();
                endforeach;
            }
            echo 111;
        }
    }

    //车辆添加工单
    public function actionOrder_add(){
        $car_id = $_GET['car_id'];
        $car = Cars::find()->where(['id' => $car_id])->asArray()->one();
        $service_types = Service_type::find()->asArray()->all();
//        $services = Service::find()->asArray()->all();
        if(Yii::$app->request->post()){
            $car_code = $_POST['car_code'];
            //如果输入了车架码，就保存
            if(!empty($car_code)){
                $car_for_code = Cars::find()->where(['id' => $car_id])->one();
                $car_for_code->car_code = $car_code;
                $car_for_code->save();
            }
            $car_id = $_POST['car_id'];
            $service_id = $_POST['service_id'];
            $goods_ids = $_POST['goods_ids'];
            $car_mileage = $_POST['car_mileage'];
            $back_id = $_GET['back_id'];
            $service = Service::find()->where(['id' => $service_id])->asArray()->one();
            $order = new Orders();
            //判断订单池里面还有无该车的等待开工（status=11）的订单，有的话，order_no共用，没有的话，新建一个
            $order_others = Orders::find()->where("status < 21")->andWhere(['car_id' => $car_id])->asArray()->one();
            if(!empty($order_others['order_no'])){
                $order_no = $order_others['order_no'];
                $worker_id = $order_others['worker_id'];
                $status = $order_others['status'];
            }else{
                $order_no = $this->_get_order_no();
                $worker_id = 0;
                $status = 11;
            }
            $order->order_no = $order_no;
            $order->car_id = $car_id;
            if(!empty($back_id)){
                $order->order_by = 1;//返工单为1，最优先
            }else{
                $order->order_by = 9;
            }
            $order->create_time = time();
            $order->take_sp = $_POST['take_sp'];
            $order->service_id = $service_id;
            $order->worker_id = $worker_id;
            $order->service_name = $service['name'];
            $order->price = $service['price'];
            $order->mileage = $car_mileage;
            $order->create_id = Yii::$app->session['user_id'];
            $order->store_id = Yii::$app->session['store_id'];
            $order->status = $status;
            if($order->save()){
                //下单转到另外一个界面
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
                if(!empty($back_id)){
                    $back = Order_back::find()->where(['id' => $back_id])->one();
                    $back->back_order = $order['id'];
                    $back->save();
                }
                Yii::$app->getSession()->setFlash('success','下单成功！');
            }else{
                Yii::$app->getSession()->setFlash('error','系统繁忙，请稍后重试');
            }
            return $this->redirect("index.php?r=car/orders&car_id=".$car_id);
        }else {
            Yii::$app->getSession()->setFlash('success', '请选择需要的服务类型！');
            //查是否有未开工的订单，是的话直接获取这些订单的公里数
            $order = Orders::find()->where("status < 21")->andWhere(['car_id' => $car_id])->orderBy('create_time')->asArray()->one();
            $mileage = $order['mileage'];
            return $this->render('order_add', [
                'car' => $car,
//                'services' => $services,
                'mileage' => $mileage,
                'service_types' => $service_types,
            ]);
        }
    }

    //根据类型获取服务
    public function actionFind_service(){
        if(Yii::$app->request->post()){
            $type_id = $_POST['type_id'];
            $services = Service::find()->where(['type_id' => $type_id])->asArray()->all();
//            echo "<select name='service_id' id='service_id' onchange='get_goods();'>";
            echo "<select name='service_id' id='service_id' >";
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