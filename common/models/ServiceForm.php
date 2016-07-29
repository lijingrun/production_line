<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 18:19
 */
namespace common\models;

use Yii;
use yii\base\Model;

class ServiceForm extends Model{
    public $name;
    public $type_id;
//    public $price;
    public $use_time;
    public $check_km;

    public function rules()
    {
        return [
            [['check_km','name','type_id','use_time'],'required','message' => '内容不能为空'],
//            [['check_km'],['integerOnly' => 'true'],'message' => '请输入整数'],
        ];
    }

}