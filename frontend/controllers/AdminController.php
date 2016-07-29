<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/3
 * Time: 11:16
 */
namespace frontend\controllers;

use Yii;
use yii\web\Controller;


class AdminController extends Controller{

    //前台销售后台首页
    public function actionIndex(){
        if($this->_a_login()){

        }else{
            return $this->render('login');
        }
    }

    public function actionLogin(){
        $request = Yii::$app->request;
        $user_name = $request->get('username');
        echo $user_name;
        exit;
    }

}