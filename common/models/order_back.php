<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/9
 * Time: 21:48
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Order_back extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_back}}';
    }
}