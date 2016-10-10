<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/9/30
 * Time: 10:59
 */
namespace frontend\controllers;

use common\models\Notice;
use common\models\Step;
use common\models\Type;
use common\models\Worker;
use common\models\Worker_step;
use Yii;
use yii\web\Controller;


class StaffController extends Controller{


    public function actionIndex(){
        $workers =  Worker::find()->asArray()->all();


        return $this->render('index',[
            'workers' => $workers,
        ]);
    }

    public function actionAdd(){
        if(Yii::$app->request->post()){
            $worker_no = $_POST['worker_no'];
            if(empty($worker_no)){
                echo 222;
                exit;
            }else{
                $worker = Worker::find()->where("worker_no like '".$worker_no."'")->asArray()->one();
                if(!empty($worker)){
                    echo 333;
                    exit;
                }
                $new_worker = new Worker();
                $new_worker->worker_no = $worker_no;
                if($new_worker->save()){
                    echo 111;
                    exit;
                }else{
                    echo 444;
                    exit;
                }
            }
        }
    }

    public function actionDel(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            if(Worker::deleteAll("id =".$id)){
                echo 111;
                exit;
            }
        }
    }


    public function actionDetail(){
        $worker_no = $_GET['worker_no'];
        $worker_step = Worker_step::find()->where("worker_no like '".$worker_no."'")->asArray()->all();
        foreach($worker_step as $key=>$val):
            $worker_step[$key]['step_id'] = Step::find()->where("step_id =".$val['step_id'])->asArray()->one();
        endforeach;
        return $this->render("detail",[
            'worker_step' => $worker_step,
        ]);
    }

    public function actionChange_num(){
        if(Yii::$app->request->post()){
            $new_num = $_POST['new_num'];
            $id = $_POST['id'];
            $w_step = Worker_step::find()->where("id =".$id)->one();
            $w_step->actual_num = $new_num;
            if($w_step->save()){
                echo 111;
            }
            exit;
        }
    }

    public function actionChange_num_un(){
        if(Yii::$app->request->post()){
            $new_num = $_POST['new_num'];
            $id = $_POST['id'];
            $w_step = Worker_step::find()->where("id =".$id)->one();
            $w_step->unhealthy_num = $new_num;
            if($w_step->save()){
                echo 111;
            }
            exit;
        }
    }
}