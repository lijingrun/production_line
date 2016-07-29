<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/7
 * Time: 22:38
 */
namespace common\models;

use Yii;
use yii\base\Model;

class Car_typeForm extends Model{
    public $car_type;

    public function rules()
    {
        return [
            [['car_type'],'required','message' => '请填写汽车类型'],
            [['car_type'], 'unique', 'targetClass' => 'common\models\Car_type', 'message' => '这个类型已经被登记了！']
        ];
    }
}