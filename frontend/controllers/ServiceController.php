<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 17:14
 */
namespace frontend\controllers;
use common\models\Car_type;
use common\models\Goods_type;
use common\models\Service;
use common\models\Service_check_type;
use common\models\Service_price;
use common\models\ServiceForm;
use common\models\Service_goods;
use Yii;
use common\models\Service_type;
use yii\data\Pagination;
use yii\web\Controller;

class ServiceController extends Controller{

    public function beforeAction($action)
    {
        if(Yii::$app->session['user_role']['service'] != 'on'){
            Yii::$app->getSession()->setFlash('error','你没有权限访问！');
            return $this->goHome();
        }else{
            return $action;
        }
    }

    public function actionIndex(){
        $type_id = $_GET['type_id'];
        $all_services = Service::find();
        if($type_id != 0){
            $all_services->andWhere(['type_id' => $type_id]);
        }
        $types = Service_type::find()->asArray()->all();
        $pages = new Pagination([
            'totalCount' => $all_services->count(),
            'pageSize' => 20,
        ]);
        $services = $all_services->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('service_list',[
            'types' => $types,
            'services' => $services,
            'pages' => $pages,
            'type_id' => $type_id,
        ]);
    }

    //工时费解析
    public function actionWorker_conten(){
        $id = $_GET['id'];
        $service = Service::find()->where(['id' => $id])->asArray()->one();
        return $this->render('worker_content',[
            'service' => $service,
        ]);
    }

    //保存工时费详细
    public function actionAdd_worker_content(){
        $id = $_POST['id'];
        $data = $_POST['data'];
        $service = Service::find()->where(['id' => $id])->one();
        $service->worker_content = $data;
        if($service->save()){
            echo 111;
        }else{
            echo 222;
        }
        exit;
    }

    //工费设置
    public function actionService_price(){
        $service_id = $_GET['id'];
        $service = Service::find()->where(['id' => $service_id])->asArray()->one();
        $service_prices = Service_price::find()->where(['service_id' => $service_id])->asArray()->all();
        $car_types = Car_type::find()->asArray()->all();

        foreach($service_prices as $key=>$service_price):
            $service_prices[$key]['car_type'] = Car_type::find()->where(['type_id' => $service_price['car_type']])->asArray()->one();
        endforeach;
        if(Yii::$app->request->post()){
            $car_type = $_POST['car_type'];
            $price = $_POST['price'];
            $new_service_price = new Service_price();
            $new_service_price->car_type = $car_type;
            $new_service_price->price = $price;
            $new_service_price->service_id = $service_id;
            if($new_service_price->save()){
                Yii::$app->getSession()->setFlash('success','保存成功！');
            }else{
                Yii::$app->getSession()->setFlash('error','保存失败！');
            }
            return $this->redirect('index.php?r=service/service_price&id='.$service_id);
        }else{
            return $this->render('service_price',[
                'service' => $service,
                'service_prices' => $service_prices,
                'car_types' => $car_types,
            ]);
        }

    }

    //更改服务名称
    public function actionEdit_name(){
        if(Yii::$app->request->post()){
            $service_id = $_POST['service_id'];
            $new_name = $_POST['new_name'];
            $service = Service::find()->where(['id' => $service_id])->one();
            $service->name = $new_name;
            if($service->save()){
                echo 111;
            }else{
                echo 222;
            }
            exit;

        }
    }

    //删除工费类型
    public function actionDel_price(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            $price = new Service_price();
            if($price->deleteAll(['id' => $id])){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //服务类型
    public function actionService_type(){
        $types = Service_type::find()->where(['top_id' => 0])->asArray()->all();
        foreach($types as $key=>$type):
            $under = Service_type::find()->where(['top_id' => $type['type_id']])->asArray()->all();
            $types[$key]['under'] = $under;
        endforeach;
        return $this->render('service_type',[
            'types' => $types,
        ]);

    }

    //添加服务类型
    public function actionType_add(){
        if(Yii::$app->request->post()){
            $name = $_POST['name'];
            $top_id = $_POST['top_id'];
            $service_type = new Service_type();
            $service_type->name = $name;
            $service_type->top_id = $top_id;
            if($service_type->save()){
                Yii::$app->getSession()->setFlash('success','添加成功！');
                echo 111;
            }else{
                Yii::$app->getSession()->setFlash('error','添加失败！');
                echo 222;
            }
            return;
        }
    }


    //添加服务
    public function actionService_add(){
        $model = new ServiceForm();
        $akk_types = Service_type::find()->asArray()->all();
        $types = array();
        foreach($akk_types as $type):
            $types[$type['type_id']] = $type['name'];
        endforeach;
        if($model->load(Yii::$app->request->post()) && $model->validate()){
//            print_r($model);exit;
            $service = new Service();
            $service->name = $model['name'];
            $service->type_id = $model['type_id'];
//            $service->price = $model['price'];
            $service->use_time = $model['use_time'];
            $service->check_km = $model['check_km'];
            if($service->save()){
                Yii::$app->getSession()->setFlash('success','添加成功！');
            }else{
                Yii::$app->getSession()->setFlash('error','添加失败，请重试！');
            }
            return $this->redirect('index.php?r=service');
        }else{
            return $this->render('service_add',[
                'model' => $model,
                'types' => $types,
            ]);
        }
    }

    //服务下面包含的产品
    public function actionService_goods(){
        $service_id = $_GET['id'];
        $service_goods = Service_goods::find()->where(['service_id' => $service_id])->asArray()->all();
        foreach($service_goods as $key=>$service_good):
            $service_goods[$key]['goods_type'] = Goods_type::find()->where(['type_id' => $service_good['goods_type']])->asArray()->one();
        endforeach;
        $service = Service::find()->where(['id' => $service_id])->asArray()->one();
        $goods_types = Goods_type::find()->asArray()->all();
        return $this->render('service_goods',[
            'service_goods' => $service_goods,
            'service' => $service,
            'goods_types' => $goods_types,
        ]);
    }

    //添加服务下面需要的产品
    function actionAdd_goods(){
        if(Yii::$app->request->post()){
            $service_id = $_POST['service_id'];
            $goods_type = $_POST['goods_type'];
            $num = $_POST['num'];
            $is_must = $_POST['is_must'];
            if(empty($service_id) || empty($goods_type) || empty($num)){
                echo 222;
                exit;
            }else{
                $service_goods = new Service_goods();
                $service_goods->service_id = $service_id;
                $service_goods->goods_type = $goods_type;
                $service_goods->nums = $num;
                $service_goods->is_must = $is_must;
                if($service_goods->save()){
                    Yii::$app->getSession()->setFlash('success','添加成功！');
                    echo 111;
                }else{
                    Yii::$app->getSession()->setFlash('error','添加失败，请稍后重试！');
                    echo 222;
                }
                exit;
            }
        }else{
            echo 333;exit;
        }
    }

    //删除服务下面对应的商品
    public function actionDel_goods(){
        $id = $_POST['service_id'];
        $service_goods = new Service_goods();
        if($service_goods->deleteAll(['id' => $id])){
            echo 111;
        }else{
            echo 222;
        }
        exit;
    }

    //改变必须字段
    public function actionChange_must(){
        $id = $_POST['id'];
        $is_must = $_POST['is_must'];
        $service_goods = Service_goods::find()->where(['id' => $id])->one();
        $service_goods->is_must = $is_must;
        if($service_goods->save()){
            echo 111;
        }else{
            echo 222;
        }
        exit;
    }

    //快速检测项
    public function actionCheck_type(){
        if(Yii::$app->request->post()){
            $new_type = new Service_check_type();
            $new_type->check_type = $_POST['check_type'];
            $new_type->order = 1;
            if($new_type->save()){
                Yii::$app->getSession()->setFlash('success','添加成功');
            }else{
                Yii::$app->getSession()->setFlash('error','服务器繁忙，请稍后重试');
            }
            return $this->redirect('index.php?r=service/check_type');
        }else{
            $check_types = Service_check_type::find()->asArray()->orderBy('order')->all();
            return $this->render('check_type_list',[
                'types' => $check_types,
            ]);
        }
    }

    //修改标准工时
    public function actionEdit_time(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            $new_time = $_POST['new_time'];
            $service = Service::find()->where(['id' => $id])->one();
            $service->use_time = $new_time;
            if($service->save()){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //修改服务的详细内容
    public function actionEdit(){
        $service_types = Service_type::find()->asArray()->all();
        $service_id = $_GET['id'];
        $service = Service::find()->where(['id' => $service_id])->asArray()->one();
        if(Yii::$app->request->post()){
            $edit_service = Service::find()->where(['id' => $service_id])->one();
            $edit_service->type_id = $_POST['type_id'];
            $edit_service->check_km = $_POST['check_km'];
            $edit_service->use_time = $_POST['use_time'];
            $edit_service->name = $_POST['name'];
            if($edit_service->save()){
                Yii::$app->getSession()->setFlash('success','操作成功！');
            }else{
                Yii::$app->getSession()->setFlash('error','服务器繁忙，请稍后重试！');
            }
            return $this->redirect('index.php?r=service');
        }else{
            return $this->render('edit',[
                'service' => $service,
                'service_types' => $service_types,
            ]);
        }
    }

    public function actionDel_service_type(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            if(Service_type::deleteAll(['type_id' => $id])){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //删除检测项目
    public function actionDel_type(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            if(Service_check_type::deleteAll(['id' => $id])){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }
}