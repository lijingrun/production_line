<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/7/28
 * Time: 21:20
 */
namespace frontend\controllers;

use common\models\Step;
use common\models\Type;
use common\models\Worker_step;
use Yii;
use yii\web\Controller;


class IndexController extends Controller{

    public $enableCsrfValidation = false;
    public $layout = 'main2';

    public function actionIndex(){
        if(Yii::$app->request->post()){
//            print_r($_POST);exit;
//            $worker_no = $_POST['worker_no'];
//            $actual_num = $_POST['actual_num'];
//            $unhealthy_num = $_POST['unhealthy_num'];
//            $step_id = $_POST['step_id'];
//            $date = date("Y-m-d",time());
//            $worker_step = Worker_step::find()->where("worker_no =".$worker_no)->andWhere('date ='.$date)->andWhere('step_id ='.$step_id)->one();
//            if(empty($worker_step)){
//                $worker_step = new Worker_step();
//            }
//            $worker_step->worker_no = $worker_no;
//            $worker_step->actual_num = $actual_num;
//            $worker_step->unhealthy_num = $unhealthy_num;
//            $worker_step->step_id = $step_id;
//            $worker_step->date = $date;
//            if($worker_step->save()){
//                Yii::$app->getSession()->setFlash('success','保存成功！');
//            }else{
//                Yii::$app->getSession()->setFlash('error','服务器繁忙，请稍后重试！');
//            }
//            return $this->redirect('');
        }else {
//            $steps = Step::find()->asArray()->orderBy('add_time')->all();
            $types = Type::find()->asArray()->all();
            return $this->render('index', [
                'types' => $types,
            ]);
        }
    }

    public function actionGet_content(){
        $step_id = $_POST['step_id'];
        $step = Step::find()->where("step_id =".$step_id)->asArray()->one();
        if(!empty($step)){
            echo json_encode($step);
            exit;
        }
    }

    public function actionSave_data(){
        if(Yii::$app->request->post()){
            $worker_no = $_POST['worker_no'];
            $actual_num = $_POST['actual_num'];
            $unhealthy_num = $_POST['unhealthy_num'];
            $step_id = $_POST['step_id'];
            $date = date("Y-m-d",time());
            $date_time = strtotime($date);
            $worker_step = Worker_step::find()->where("worker_no ='".$worker_no."'")->andWhere("date =".$date_time)->andWhere("step_id =".$step_id)->one();
            if(empty($worker_step)){
                $worker_step = new Worker_step();
            }
            $worker_step->worker_no = $worker_no;
            $worker_step->actual_num = $actual_num;
            $worker_step->unhealthy_num = $unhealthy_num;
            $worker_step->step_id = $step_id;
            $worker_step->date = $date_time;
            if($worker_step->save()){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    public function actionGet_type(){
        $type_id = $_POST['type'];
        if(!empty($type_id)){
            $step = Step::find()->where("type_id =".$type_id)->asArray()->all();
            echo "<option value='0'>选择组件</option>";
            foreach($step as $val):
                echo "<option value='".$val['step_id']."'>".$val['title']."</option>";
            endforeach;
            exit;
        }
    }
}