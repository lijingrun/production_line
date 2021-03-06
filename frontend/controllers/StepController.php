<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/7/28
 * Time: 16:40
 */

namespace frontend\controllers;

use common\models\Notice;
use common\models\Step;
use common\models\Type;
use Yii;
use yii\web\Controller;


class StepController extends Controller{

    public $enableCsrfValidation = false;

    public function actionIndex(){
        $types = Type::find()->asArray()->all();
        $type_id = $_GET['type_id'];
        if(!empty($type_id)){
            $steps = Step::find()->where("type_id =".$type_id)->asArray()->orderBy("add_time")->all();
        }else {
            $steps = Step::find()->asArray()->orderBy("add_time")->all();
        }
        return $this->render('index',[
            'steps' => $steps,
            'types' => $types,
            'type_id' => $type_id,
        ]);
    }

    public function actionEdit(){
        $step_id = $_GET['step_id'];
        $step = Step::find()->where("step_id =".$step_id)->one();
        $types = Type::find()->asArray()->all();
        if(Yii::$app->request->post()){
            $title = $_POST['title'];
            $content = $_POST['content'];
            $step['title'] = $title;
            $step['content'] = $content;
            $step['price'] = $_POST['price'];
            $step['plan'] = $_POST['plan'];
            $step['type_id'] = $_POST['type_id'];
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
                    'types' => $types,
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
        $types = Type::find()->asArray()->all();

        return $this->render('step_add',[
            'types' => $types,
        ]);

    }

    public function actionAdd_ajax(){
        if(Yii::$app->request->post()){
            $content = $_POST['content'];
            $title = $_POST['title'];
            $step_id = $_POST['step_id'];
            $plan = $_POST['plan'];
            $type_id = $_POST['type_id'];
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
                $step->plan = $plan;
                $step->type_id = $type_id;
                $step->price = $_POST['price'];
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

    public function actionAdd_type(){
        $types = Type::find()->asArray()->all();

        return $this->render("type",[
            'types' => $types,
        ]);
    }

    public function actionAdd_type_ajax(){
        if(Yii::$app->request->post()) {
            $type_name = $_POST['type_name'];
            if (!empty($type_name)) {
                $type = new Type();
                $type->name = $type_name;
                if ($type->save()) {
                    echo 111;
                } else {
                    echo 222;
                }
            }
        }
    }

    public function actionAdd_notice(){
        if(Yii::$app->request->post()){
            $notice_text = $_POST['notice'];
            $notice = Notice::find()->one();
            if(empty($notice)){
                $notice = new Notice();
                $notice->notice = $notice_text;
            }else{
                $notice->notice = $notice_text;
            }
            if($notice->save()){
                Yii::$app->getSession()->setFlash('success','保存成功！');
            }else{
                Yii::$app->getSession()->setFlash('error','保存失败，请重新保存！');
            }
            return $this->redirect("index.php?r=step/add_notice");
        }else{
            $notice = Notice::find()->asArray()->one();
            return $this->render("notice",[
                'notice' => $notice,
            ]);
        }
    }

    public function actionDel_type(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            if(!empty($id)){
                if(Type::deleteAll("id =".$id)){
                    echo 111;
                    exit;
                }
            }
        }
    }

}
