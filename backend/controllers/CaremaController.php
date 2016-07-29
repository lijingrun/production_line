<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/13
 * Time: 19:06
 */
namespace backend\controllers;


use common\models\Carema;
use common\models\Members;
use common\models\Orders;
use common\models\Weixin;
use Yii;
use yii\web\Controller;

class CaremaController extends Controller{
    public $layout = 'mobile';
    public function actionCheck(){
        $order_id = $_GET['order_id'];
        $order = Orders::find()->where(['id' => $order_id])->andWhere("status < 30")->asArray()->one();
        $carema = Carema::find()->where(['worker_id' => $order['worker_id']])->asArray()->one();
//        print_r($carema);exit;
        return $this->render('check',[
            'carema' => $carema,
        ]);
    }
}