<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/22
 * Time: 16:33
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Car_reason extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%car_reason}}';
    }
}