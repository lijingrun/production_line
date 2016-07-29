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
 * 工人注册form
 */
class MemberRegisterForm extends Model{
    public $user_name;
    public $phone;
	public $rec_numbers;
    public $open_id;
    public $password;


    public function rules()
    {
        return [
            [['user_name','phone'],'required','message'=>'内容不能为空！'],
            [['user_name'],'unique','targetClass' => 'common\models\Members', 'message' => '该用户名已经被注册了！'],
            [['phone'],'unique','targetClass' => 'common\models\Members', 'message' => '该号码已经注册了！'],
        ];
    }
}
