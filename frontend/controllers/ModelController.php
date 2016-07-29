<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/22
 * Time: 14:05
 */
/**
 * 车型信息录入
 */
namespace frontend\controllers;

use common\models\Car_brand;
use common\models\Car_model;
use common\models\Car_style;
use Yii;
use yii\web\Controller;

class ModelController extends Controller{

    public function beforeAction($action)
    {
        if(Yii::$app->session['user_role']['orders'] != 'on'){
            Yii::$app->getSession()->setFlash('error','你没有权限访问！');
            return $this->goHome();
        }else{
            return $action;
        }
    }

    public function actionIndex(){
        $brand_name = $_GET['brand_name'];

        if(!empty($brand_name)){
            $brands = Car_brand::find()->where(['like', 'brand_name', $brand_name])->asArray()->all();
        }else{
            $brands = Car_brand::find()->asArray()->all();
        }

        return $this->render('car_brand',[
            'brands' => $brands,
            'brend_name' => $brand_name,
        ]);
    }

    //增加车辆品牌
    public function actionBrand_add(){
        if(Yii::$app->request->post()){
            $brand_name = $_POST['brand_name'];
            //查品牌是否存在
            $car_brand = Car_brand::find()->where(['brand_name' => $brand_name])->count();
            if($car_brand != 0){
                echo 222;
                exit;
            }
            $new_brand = new Car_brand();
            $new_brand->brand_name = $brand_name;
            if($new_brand->save()){
                echo 111;
                exit;
            }else{
                echo 333;
                exit;
            }
        }
    }

    //车辆型号
    public function actionModel_list(){
        $brand_id = $_GET['brand_id'];
        $brand = Car_brand::find()->where(['brand_id' => $brand_id])->asArray()->one();
        $models = Car_model::find()->where(['brand_id' => $brand_id])->asArray()->orderBy('model_name')->all();



        return $this->render('model_list',[
            'brand' => $brand,
            'models' => $models,
        ]);
    }

    //添加系列
    public function actionAdd_model(){
        if(Yii::$app->request->post()){
            $model_name = $_POST['model_name'];
            $brand_id = $_POST['brand_id'];
            $year = $_POST['year'];
            $count = Car_model::find()->where(['brand_id' => $brand_id])->andWhere(['model_name' => $model_name])->count();
            if($count != 0){
                echo 222;
                exit;
            }
            $model = new Car_model();
            $model->brand_id = $brand_id;
            $model->model_name = $model_name;
            $model->year = $year;
            if($model->save()){
                echo 111;
                exit;
            }else{
                echo 333;
                exit;
            }
        }
    }

    //添加车型
    public function actionStyle(){
        $id = $_GET['id'];
        $model = Car_model::find()->where(['id' => $id])->asArray()->one();
        $styles = Car_style::find()->where(['model_id' => $id])->asArray()->all();
            return $this->render('style_list',[
                'model' => $model,
                'styles' => $styles,
            ]);
    }

    //增加车型
    public function actionStyle_add(){
        if(Yii::$app->request->post()){
            $style = new Car_style();
            $style->style_name = $_POST['style_name'];
            $style->model_id = $_POST['model_id'];
            if($style->save()){
                echo 111;
            }else{
                echo 222;
            }
        }
    }

}