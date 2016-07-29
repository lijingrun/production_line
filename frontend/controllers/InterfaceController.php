<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/4/14
 * Time: 10:44
 */

namespace frontend\controllers;

use common\models\Index;
use common\models\Upload_imageForm;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

class InterfaceController extends Controller{



    public function actionIndex(){
        $index_images = Index::find()->asArray()->all();

        return $this->render('index',[
            'index_images' => $index_images,
        ]);

    }


    //添加图片
    public function actionAdd_model(){
        $model = new Upload_imageForm();
        $id = $_GET['id'];
        if(!empty($id)){
            $index = Index::find()->where(['id' => $id])->asArray()->one();
        }
        if(Yii::$app->request->post()){
            $model->file = UploadedFile::getInstance($model, 'file');
            $file_url = 'upload/index/'.time().'.'.$model->file->extension;
            if(empty($id)){
                $ind = new Index();
                $ind->images = $file_url;
            }else{
                $ind = Index::find()->where(['id' => $id])->one();
                $ind->images = $file_url;
            }
            $ind->save();
            if ($model->file && $model->validate()) {
                $model->file->saveAs($file_url);
            }
            Yii::$app->getSession()->setFlash('success','保存成功！');
            return $this->redirect('index.php?r=interface/add_model&id='.$ind->id);
        }else{
            return $this->render('add_model',[
                'model' => $model,
                'index' => $index,
            ]);
        }

    }

    //删除
    public function actionDel(){
        $id = $_POST['id'];
        if(Index::deleteAll(['id' => $id])){
            echo 111;
        }else{
            echo 222;
        }
        exit;
    }

    //添加内容
    public function actionAdd_content(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            $data = $_POST['data'];
            if(!empty($id)){
                $index = Index::find()->where(['id' => $id])->one();
            }else{
                $index = new Index();
            }
            $index->content = $data;
            if($index->save()){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //修改主页order
    public function actionChange_order(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            $order = $_POST['order'];
            if(!empty($id)){
                $index = Index::find()->where(['id' => $id])->one();
                $index->order = $order;
                if($index->save()){
                    echo 111;
                }else{
                    echo 222;
                }
            }
        }
        exit;
    }

}