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

class Car_addForm extends Model{
    public $car_no;
    public $car_type;

    public function rules()
    {
        return [
            [['car_no'], 'unique', 'targetClass' => 'common\models\Cars', 'message' => '这个车牌号码已经被登记了！'],
            [['car_type'],'required','message' => '请选汽车类型'],
        ];
    }
}