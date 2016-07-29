<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/10
 * Time: 23:09
 */
namespace frontend\controllers;

use common\models\WorkerForm;
use Yii;
use yii\web\Controller;

use common\models\Worker;
use common\models\Store;

class WorkerController extends Controller{

    public function actionIndex(){
        $workers = Worker::find()->asArray()->all();
        foreach($workers as $key=>$worker):
            $workers[$key]['store'] = Store::find()->where(['store_id' => $worker['store_id']])->asArray()->one();
        endforeach;
        return $this->render('worker',[
            'workers' => $workers,
        ]);
    }

    public function beforeAction($action)
    {
        if(Yii::$app->session['user_role']['worker'] != 'on'){
            Yii::$app->getSession()->setFlash('error','你没有权限访问！');
            return $this->goHome();
        }else{
            return $action;
        }
    }

    //添加工人
    public function actionAdd(){
        $model = new WorkerForm();
        $all_store = Store::find()->asArray()->all();
        $stores = array();
        foreach($all_store as $store):
            $stores[$store['store_id']] = $store['store_name'];
        endforeach;
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $worker = new Worker();
            $worker->worker_name = $model['worker_name'];
            $worker->store_id = $model['store_id'];
            $worker->setPassword($model['password']);
            if($worker->save()){
                Yii::$app->getSession()->setFlash('success','添加成功！');
            }else{
                Yii::$app->getSession()->setFlash('error','添加失败！');
            }
            return $this->redirect("index.php?r=worker");
        }else {
            return $this->render('worker_add', [
                'model' => $model,
                'stores' => $stores,
            ]);
        }
    }
}