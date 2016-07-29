<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/7/28
 * Time: 16:40
 */

namespace frontend\controllers;

use common\models\Step;
use Yii;
use yii\web\Controller;


class StepController extends Controller{

    public function actionIndex(){
        $steps = Step::find()->asArray()->orderBy("add_time")->all();
        return $this->render('index',[
            'steps' => $steps,
        ]);
    }

    public function actionEdit(){
        $step_id = $_GET['step_id'];
        $step = Step::find()->where("step_id =".$step_id)->one();
        if(Yii::$app->request->post()){
            $title = $_POST['title'];
            $content = $_POST['content'];
            $step['title'] = $title;
            $step['content'] = $content;
            if($step->save()){
                Yii::$app->getSession()->setFlash('success','修改成功！');
                return $this->redirect("index.php?r=step");
            }else{

            }
        }else{
            if(empty($step)){
                Yii::$app->getSession()->setFlash("error","该规程不存在！");
            }else{
                return $this->render('step_edit',[
                    'step' => $step,
                ]);
            }
        }
    }

    public function actionGet_content(){
        $step_id = $_POST['step_id'];
        if(!empty($step_id)) {
            $step = Step::find()->where("step_id =" . $step_id)->one();
            echo $step['content'];
            exit;
        }
    }

    public function actionAdd(){


            return $this->render('step_add');

    }

    public function actionAdd_ajax(){
        if(Yii::$app->request->post()){
            $content = $_POST['content'];
            $title = $_POST['title'];
            $step_id = $_POST['step_id'];
            if(!empty($content)){
                if(!empty($step_id)){
                    $step = Step::find()->where("step_id =".$step_id)->one();
                }else{
                    $step = new Step();
                }
                if(empty($step['add_time']))
                $step->add_time = time();
                $step->content = $content;
                $step->title = $title;
                if($step->save()){
                    echo 111;
                }else{
                    echo 222;
                }
            }
        }
    }

    public function actionDel(){
        if(Yii::$app->request->post()){
            $step_id = $_POST['step_id'];
            if(!empty($step_id)){
                Step::deleteAll("step_id =".$step_id);
                echo 111;
                exit;
            }
        }
    }

}
