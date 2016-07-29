<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/7/28
 * Time: 21:20
 */
namespace frontend\controllers;

use common\models\Step;
use Yii;
use yii\web\Controller;


class IndexController extends Controller{
    public function actionIndex(){
        $steps = Step::find()->asArray()->orderBy('add_time')->all();

        return $this->render('index',[
            'steps' => $steps,
        ]);
    }

    public function actionGet_content(){
        $step_id = $_POST['step_id'];
        $step = Step::find()->where("step_id =".$step_id)->one();
        if(!empty($step)){
            echo $step['content'];
            exit;
        }
    }

    public function actionSave_data(){
        if(Yii::$app->request->post()){

        }
    }
}