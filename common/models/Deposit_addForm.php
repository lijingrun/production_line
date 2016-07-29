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

class Deposit_addForm extends Model {
	public $plan_id;
	public $description;

    public function rules()
    {
        return [
            [['plan_id'],'required','message' => '请选充值套餐'],
            [['description'],'required','message' => '请填写附加信息'],
        ];
    }
}
