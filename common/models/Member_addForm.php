<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/7
 * Time: 20:45
 */
namespace common\models;

use Yii;
use yii\base\Model;
/**
 * 前台会员注册form
 */
class Member_addForm extends Model{
    public $user_name;
    public $phone;
    public $open_id;
    public $password;
    public $rec_numbers;


    public function rules()
    {
        return [
            [['user_name','phone'],'required','message'=>'内容不能为空！'],
            [['phone'],'unique','targetClass' => 'common\models\Members', 'message' => '该电话号码已经注册了！'],
        ];
    }
}